<?php

namespace App\Http\Middleware;

use App\Services\ScandinavianAuction\Dto\CurrentBitDto;
use App\Services\ScandinavianAuction\ScandinavianAuctionService;
use App\Services\ScandinavianAuction\ScandinavianAuctionTaxonomy;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Predis\Protocol\Text\Handler\ErrorResponse;

class ScandinavianAuction
{
    /**
     *
     */
    const CONFLICT_ERROR_RESPONSE_MESSAGE = 'Вашу ставку перебили, для продолжения участия в аукционе, сделайте новую ставку.';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
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
