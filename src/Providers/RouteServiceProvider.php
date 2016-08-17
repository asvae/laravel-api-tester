<?php

namespace Asvae\ApiTester\Providers;

use Asvae\ApiTester\Http\Middleware\DebugState;
use Asvae\ApiTester\Http\Middleware\DetectRoute;
use Asvae\ApiTester\Http\Middleware\PreventRedirect;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @type \Illuminate\Foundation\Http\Kernel
     */
    protected $kernel;

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
            'as' => 'api-tester.',
            'prefix' => config('api-tester.route'),
            'namespace' => $this->getNamespace(),
            'middleware' => $this->getMiddleware(),
        ], function () {
            $this->requireRoutes();
        });
    }

    /**
     * @param Router $router
     * @param Kernel|\Illuminate\Foundation\Http\Kernel $kernel
     */
    public function boot(Router $router, Kernel $kernel)
    {
        $this->map($router);

        $this->kernel = $kernel;

        // The middleware is used to intercept every request with specific header
        // so that laravel can tell us, which route the request belongs to.
        $kernel->prependMiddleware(DetectRoute::class);
        $kernel->prependMiddleware(PreventRedirect::class);
    }

    protected function getMiddleware()
    {
        $middleware = config('api-tester.middleware');

        return $middleware;
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

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}
