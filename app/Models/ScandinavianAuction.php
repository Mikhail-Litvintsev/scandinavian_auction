<?php

namespace App\Models;

use App\Services\ScandinavianAuction\Dto\CurrentBitDto;
use App\Services\ScandinavianAuction\ScandinavianAuctionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScandinavianAuction extends Model
{
    use HasFactory;

    protected $table = 'scandinavian_auctions';
    protected $fillable = ['id', 'leaderName', 'step', 'currentBit', 'status'];

    public static function upsertCurrentBitDto(CurrentBitDto $currentBitDto): ScandinavianAuction
    {
        $id = $currentBitDto->currentAuctionId ?? 0;
        if ($id) {
            ScandinavianAuction::where('id', $id)->update(self::getDataFromCurrentBitDto($currentBitDto));
            $model = ScandinavianAuction::find($id);
        } else {
            $model = ScandinavianAuction::create(
                self::getDataFromCurrentBitDto(
                    app(ScandinavianAuctionService::class)->getDefaultBit()
                )
            );
        }
        self::setOtherInActive($model->id);
        return $model;
    }

    public static function getDataFromCurrentBitDto(CurrentBitDto $currentBitDto): array
    {
        return [
            'leaderName' => $currentBitDto->leaderName,
            'step' => $currentBitDto->step,
            'currentBit' => $currentBitDto->currentBit,
            'status' => true,
        ];
    }

    public static function setOtherInActive(int $id): bool
    {
        return ScandinavianAuction::where('id', '<>', $id)->where(['status' => true])->update(['status' => false]);
    }

    public static function setOneInActive(int $id): bool
    {
        return self::where(['id' => $id])->update(['status' => false]);
    }
}
