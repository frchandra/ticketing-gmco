<?php

namespace App\Providers;


use App\Http\Service\EmailService;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton(EmailService::class, function (){
            return new EmailService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(){

    }
}
