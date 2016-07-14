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
        $router->group(['namespace' => $this->getNamespace()], function () {
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
        $className = explode('\\', static::class);
        $className = array_slice($className, 0, -2);

        return implode('\\', $className).'\Http\Controllers';
    }

    /**
     * @return string
     */
    protected function requireRoutes()
    {
        $className = explode('\\', static::class);
        $className = array_slice($className, 1, -2);
        $className = implode('/', $className);

        require app_path($className).'/Http/routes.php';
    }
}