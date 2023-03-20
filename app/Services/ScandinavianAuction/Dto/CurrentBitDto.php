<?php

namespace App\Services\ScandinavianAuction\Dto;

class CurrentBitDto
{
    /**
     * @var int|null
     */
    public ?int $currentAuctionId;
    /**
     * @var int
     */
    public int $currentBit;
    /**
     * @var int
     */
    public int $step;
    /**
     * @var string|null
     */
    public ?string $leaderName;
}
