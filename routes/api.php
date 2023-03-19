<?php

use App\Http\Controllers\Api\V1\ScandinavianAuctionCostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/get_bit', [ScandinavianAuctionCostController::class, 'getBit']);
Route::middleware('scandinavianAuction')->post('/set_bit', [ScandinavianAuctionCostController::class, 'setBit']);
Route::get('/finish', [ScandinavianAuctionCostController::class, 'finish']);
Route::get('/get_results', [ScandinavianAuctionCostController::class, 'getResults']);
