<?php

namespace App\Http\Middleware;

use App\Services\ScandinavianAuction\Dto\CurrentBitDto;
use App\Services\ScandinavianAuction\ScandinavianAuctionService;
use App\Services\ScandinavianAuction\ScandinavianAuctionTaxonomy;
use Closure;
use Illuminate\Http\Request;
use Predis\Protocol\Text\Handler\ErrorResponse;

class ScandinavianAuction
{
    const CONFLICT_ERROR_RESPONSE_MESSAGE = 'Вашу ставку перебили, для продолжения участия в аукционе, сделайте новую ставку.';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $requestBit = (int)$request->post(ScandinavianAuctionTaxonomy::PLAYER_BIT_KEY);
        if (app(ScandinavianAuctionService::class)->isBitAvailable($requestBit) === false) {
            return response(self::CONFLICT_ERROR_RESPONSE_MESSAGE, 409);
        }
        return $next($request);
    }
}
