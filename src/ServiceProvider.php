<?php

namespace Asvae\ApiTester;

use Asvae\ApiTester\Contracts\RequestRepositoryInterface;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Providers\RouteServiceProvider;
use Asvae\ApiTester\Storages\JsonStorage;
use Illuminate\Contracts\Foundation\Application;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        if (!defined('API_TESTER_PATH')) {
            define('API_TESTER_PATH', realpath(__DIR__ . '/../'));
        }

        $this->app->register(RouteServiceProvider::class);

        $this->mergeConfigFrom(API_TESTER_PATH . '/config/api-tester.php',
            'api-tester');

        $this->loadViewsFrom(API_TESTER_PATH . '/resources/views',
            'api-tester');

        $this->app->bind(
            RouteRepositoryInterface::class,
            config('api-tester.repositories.routes')
        );

        $this->app->bind(
            RequestRepositoryInterface::class,
            config('api-tester.repositories.requests')
        );

        $this->app->bind(StorageInterface::class, function (Application $app) {
            $app->make(JsonStorage::class, [config('api-tester.storage.path'), config('api-tester.storage.file')]);
        });
    }

    public function boot()
    {
        $this->publishes([
            API_TESTER_PATH . '/config/api-tester.php' => config_path('api-tester.php'),
        ], 'config');
    }
}
