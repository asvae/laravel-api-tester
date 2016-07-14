<?php

namespace Asvae\ApiTester\Providers;

use Illuminate\Routing\Router;

class RouteServiceProvider extends \Illuminate\Foundation\Support\Providers\RouteServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * Module
     * ├ Http
     * │ └ routes.php
     * │
     * └ Providers
     *    └ [static]
     *
     * @param  Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group([
            'namespace' => $this->getNamespace(),
            'middleware' => ['web'],
        ], function () {
            $this->requireRoutes();
        });
    }

    public function boot(Router $router)
    {
        parent::boot($router);

        $this->loadViewsFrom(__DIR__.'/../resources/assets/views', 'api-tester');
        $this->publishes([
            __DIR__.'/../resources/assets/build' => public_path('vendor/api-tester'),
        ], 'public');
    }

    /**
     * Get module namespace
     *
     * @return string
     */
    protected function getNamespace()
    {
        return 'Asvae\ApiTester\Http\Controllers';
    }

    /**
     * @return string
     */
    protected function requireRoutes()
    {
        require __DIR__.'/../Http/routes.php';
    }
}