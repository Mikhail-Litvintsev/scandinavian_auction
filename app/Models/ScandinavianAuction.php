<?php

namespace App\Models;

use App\Services\ScandinavianAuction\Dto\CurrentBitDto;
use App\Services\ScandinavianAuction\ScandinavianAuctionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id [int]
 */
class ScandinavianAuction extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'scandinavian_auctions';
    /**
     * @var string[]
     */
    protected $fillable = ['leaderName', 'step', 'currentBit', 'status'];

    /**
     * @param CurrentBitDto $currentBitDto
     * @return ScandinavianAuction
     */
    public static function setCurrentBitDto(CurrentBitDto $currentBitDto): ScandinavianAuction
    {
        $id = $currentBitDto->currentAuctionId ?? 0;
        $model = ScandinavianAuction::updateOrCreate(['id' => $id, 'status' => true],
            self::getDataFromCurrentBitDto($currentBitDto));
        self::setOtherInActive($model->id);
        return $model;
    }

    /**
     * @param CurrentBitDto $currentBitDto
     * @return array
     */
    public static function getDataFromCurrentBitDto(CurrentBitDto $currentBitDto): array
    {
        return [
            'leaderName' => $currentBitDto->leaderName,
            'step' => $currentBitDto->step,
            'currentBit' => $currentBitDto->currentBit,
            'status' => true,
        ];
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function setOtherInActive(int $id): bool
    {
        return ScandinavianAuction::where('id', '<>', $id)->where(['status' => true])->update(['status' => false]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function setOneInActive(int $id): bool
    {
        return self::where(['id' => $id])->update(['status' => false]);
    }
}
