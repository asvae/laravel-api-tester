<?php

namespace Asvae\ApiTester\Providers;

use Asvae\ApiTester\Http\Middleware\DebugState;
use Asvae\ApiTester\Http\Middleware\DetectRouteMiddleware;
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

    public function boot(Router $router)
    {
        $this->map($router);

        $this->kernel = $this->app->make(Kernel::class);

        // The middleware is used to intercept every request with specific header
        // so that laravel can tell us, which route the request belongs to.
        $this->kernel->prependMiddleware(DetectRouteMiddleware::class);
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
