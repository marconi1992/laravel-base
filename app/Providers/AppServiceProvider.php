<?php

namespace App\Providers;

use App\Contracts\Repositories\IActivationRepository;
use App\Contracts\Services\IActivationService;
use App\Repositories\ActivationRepository;
use App\Services\ActivationService;
use Illuminate\Database\Connection;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IActivationRepository::class, function($app){
            return new ActivationRepository($app->make(Connection::class));
        });
        $this->app->singleton(IActivationService::class, function($app){
            return new ActivationService($app->make(Mailer::class), $app->make(IActivationRepository::class));
        });
    }
}
