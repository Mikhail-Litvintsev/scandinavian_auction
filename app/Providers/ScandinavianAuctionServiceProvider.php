<?php

namespace App\Providers;

use App\Services\ScandinavianAuction\ScandinavianAuctionService;
use Illuminate\Support\ServiceProvider;

class ScandinavianAuctionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\ScandinavianAuction\ScandinavianAuctionService', function ($app) {
            return new ScandinavianAuctionService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
