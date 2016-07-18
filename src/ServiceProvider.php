<?php

namespace Asvae\ApiTester;

use Asvae\ApiTester\Providers\RouteServiceProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->mergeConfigFrom(__DIR__.'/config/api-tester.php', 'api-tester');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/api-tester.php' => config_path('api-tester.php'),
        ], 'config');
    }
}
