<?php

namespace App\Providers;

use App\Http\Service\SeatService;
use Illuminate\Support\ServiceProvider;

class SeatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton(SeatService::class, function (){
            return new SeatService();
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
