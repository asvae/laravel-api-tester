<?php

namespace Asvae\ApiTester;

use Asvae\ApiTester\Contracts\RequestRepositoryInterface;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Providers\RouteServiceProvider;
use Asvae\ApiTester\Repositories\RouteRepository;
use Asvae\ApiTester\Storages\JsonStorage;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        if (!defined('API_TESTER_PATH')) {
            define('API_TESTER_PATH', realpath(__DIR__ . '/../'));
        }

        $this->mergeConfigFrom(API_TESTER_PATH . '/config/api-tester.php', 'api-tester');

        // Do nothing if api-tester is disabled
        if (!$this->app['config']['api-tester.enabled']) {
            return;
        }

        $this->app->singleton(RouteRepositoryInterface::class, function (Container $app) {
            $repositories = [];
            foreach (config('api-tester.route_repositories') as $repository) {
                $repositories[] = $app->make($repository);
            }

            return $app->make(RouteRepository::class, ['repositories' => $repositories]);
        });

        $this->app->singleton(
            RequestRepositoryInterface::class,
            config('api-tester.request_repository')
        );

        $this->app->singleton(StorageInterface::class, function (Application $app) {

            // Defined driver
            $driver = config('api-tester.storage_drivers')[config('api-tester.storage_driver')];

            return $app->make($driver['class'], $driver['options']);
        });

        $this->loadViewsFrom(API_TESTER_PATH . '/resources/views', 'api-tester');


        $this->app->register(RouteServiceProvider::class);
    }

    public function boot()
    {
        $this->publishes([API_TESTER_PATH . '/config/api-tester.php' => config_path('api-tester.php')], 'config');


    }
}
