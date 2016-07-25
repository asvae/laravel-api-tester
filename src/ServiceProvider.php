<?php

namespace Asvae\ApiTester;

use Asvae\ApiTester\Contracts\RequestRepositoryInterface;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Providers\RouteServiceProvider;
use Asvae\ApiTester\Storages\JsonStorage;
use Illuminate\Foundation\Application;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->mergeConfigFrom(__DIR__ . '/../config/api-tester.php',
            'api-tester');

        $this->app->bind(
            RouteRepositoryInterface::class,
            config('api-tester.repositories.routes')
        );

        $this->app->bind(
            RequestRepositoryInterface::class,
            config('api-tester.repositories.requests')
        );

        $this->app->bind(StorageInterface::class, function(Application $app){
            $app->make(JsonStorage::class, [config('api-tester.storage.path'), config('api-tester.storage.file')]);
        });

    }

    public function boot()
    {
        $this->publishes([__DIR__ . '/config/api-tester.php' => config_path('api-tester.php'),], 'config');
        $this->loadViewsFrom(__DIR__ . '/../resources/assets/views', 'api-tester');
    }
}
