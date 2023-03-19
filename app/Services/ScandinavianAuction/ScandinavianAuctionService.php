<?php

namespace App\Services\ScandinavianAuction;

use App\Events\UpdateCurrentBit;
use App\Models\ScandinavianAuction;
use App\Services\ScandinavianAuction\Dto\CurrentBitDto;
use Illuminate\Support\Facades\Cache;

class ScandinavianAuctionService
{
    public function __construct()
    {
        $this->setupCurrentBit();
    }

    private function setupCurrentBit(): void
    {
        if (Cache::get(ScandinavianAuctionTaxonomy::CACHE_KEY_CURRENT_BIT_DTO) === null) {
            $this->setDefaultBit();
        }
    }

    public function setDefaultBit(): self
    {
        $this->setBit($this->getDefaultBit(), true);
        return $this;
    }

    public function setBit(CurrentBitDto $currentBitDto, bool $force = false): self
    {
        if ($force === false) {
            if ($this->isBitAvailable($currentBitDto->currentBit) === false) {
                return $this;
            }
        }
        $this->setCache($currentBitDto);
        $model = ScandinavianAuction::upsertCurrentBitDto($currentBitDto);
        $currentBitDto->currentAuctionId = $model->id;
        $this->setCache($currentBitDto);
        return $this;
    }

    private function setCache(CurrentBitDto $currentBitDto): void
    {
        $currentBitDtoCache = serialize($currentBitDto);
        Cache::set(ScandinavianAuctionTaxonomy::CACHE_KEY_CURRENT_BIT_DTO, $currentBitDtoCache);
    }

    public function getDefaultBit(): CurrentBitDto
    {
        $currentBitDto = new CurrentBitDto();
        $currentBitDto->currentBit = ScandinavianAuctionTaxonomy::START_BIT;
        $currentBitDto->step = ScandinavianAuctionTaxonomy::STEP;
        $currentBitDto->leaderName = null;
        return $currentBitDto;
    }

    public function getCurrentBit(): CurrentBitDto
    {
        $this->setupCurrentBit();
        $currentBitDto = Cache::get(ScandinavianAuctionTaxonomy::CACHE_KEY_CURRENT_BIT_DTO);
        return unserialize($currentBitDto);
    }


    public function event(): self
    {
        event(new UpdateCurrentBit($this->getCurrentBit()));
        return $this;
    }

    public function isBitAvailable(int $requestBit): bool
    {
        $currentBitDto = $this->getCurrentBit();
        $availableBit = $currentBitDto->currentBit + $currentBitDto->step;
        return $availableBit === $requestBit;
    }

    public function reload(): self
    {
        ScandinavianAuction::setOneInActive(
            $this->getCurrentBit()
                ->currentAuctionId ?? 0
        );
        Cache::delete(ScandinavianAuctionTaxonomy::CACHE_KEY_CURRENT_BIT_DTO);
        $this->setupCurrentBit();
        $this->event();
        return $this;
    }
}
