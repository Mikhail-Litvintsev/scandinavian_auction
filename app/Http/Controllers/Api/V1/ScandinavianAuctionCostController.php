<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ScandinavianAuction;
use App\Services\ScandinavianAuction\Dto\CurrentBitDto;
use App\Services\ScandinavianAuction\ScandinavianAuctionService;
use App\Services\ScandinavianAuction\ScandinavianAuctionTaxonomy;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ScandinavianAuctionCostController extends Controller
{
    /**
     * @return Application|ResponseFactory|Response
     */
    public function getBit()
    {
        $currentBit = app(ScandinavianAuctionService::class)->getCurrentBit();
        return response(['success' => true, 'data' => $currentBit]);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function setBit(Request $request)
    {
        $currentBit = new CurrentBitDto();
        $currentBit->currentAuctionId = $request->post(ScandinavianAuctionTaxonomy::CURRENT_AUCTION_ID_KEY);
        $currentBit->currentBit = $request->post(ScandinavianAuctionTaxonomy::PLAYER_BIT_KEY);
        $currentBit->leaderName = $request->post(ScandinavianAuctionTaxonomy::PLAYER_NAME_KEY)
            ?? ScandinavianAuctionTaxonomy::DEFAULT_PLAYER_NAME;
        $currentBit->step = ScandinavianAuctionTaxonomy::STEP;
        app(ScandinavianAuctionService::class)->setBit($currentBit)->event();
        return response(['success' => true]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function finish()
    {
        app(ScandinavianAuctionService::class)->reload();
        return response(['success' => true]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getResults()
    {
        return response(
            ['success' => true, 'data' => ScandinavianAuction::where(['status' => false])->orderByDesc('id')->get()]
        );
    }
}
