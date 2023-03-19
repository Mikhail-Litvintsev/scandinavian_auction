<?php
namespace App\Services\ScandinavianAuction\Dto;
class CurrentBitDto
{
    public ?int $currentAuctionId;
    public int $currentBit;
    public int $step;
    public ?string $leaderName;
}
