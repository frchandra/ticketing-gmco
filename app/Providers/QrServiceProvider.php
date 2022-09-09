<?php

namespace App\Providers;

use App\Http\Service\QrService;
use Illuminate\Support\ServiceProvider;

class QrServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton(QrService::class, function (){
            return new QrService();
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
