<?php

namespace Asvae\ApiTester;

use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Asvae\ApiTester\Providers\RouteServiceProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->mergeConfigFrom(__DIR__.'/../config/api-tester.php',
            'api-tester');

        $this->app->bind(RouteRepositoryInterface::class,
            config('api-tester.repository'));
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/api-tester.php' => config_path('api-tester.php'),
        ], 'config');
    }
}
