<?php

namespace Asvae\ApiTester\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @param  Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group([
            'as'         => 'api-tester.',
            'prefix'     => config('api-tester.route'),
            'namespace'  => $this->getNamespace(),
            'middleware' => config('api-tester.middleware'),
        ], function () {
            $this->requireRoutes();
        });
    }

    public function boot(Router $router)
    {
        parent::boot($router);
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
        require __DIR__ . '/../Http/routes.php';
    }
}
