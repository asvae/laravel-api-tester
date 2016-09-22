<?php

namespace Asvae\ApiTester\Http\Middleware;

use Asvae\ApiTester\Entities\RouteInfo;
use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;

/**
 * Class DetectRouteMiddleware
 *
 * @package \Asvae\ApiTester\Http\Middleware
 */
class DetectRoute
{
    const ROUTE_INFO = 'route-info';

    /**
     * @type \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->events = $dispatcher;
    }

    public function handle(Request $request, Closure $next)
    {
        // In case the request was sent by Api Tester and route info is wanted
        // we will halt the request and output route information instead.
        if ($request->header('X-Api-Tester') === static::ROUTE_INFO) {

            // Laravel 5.1 event
            $this->events->listen('router.matched', [$this, 'handleMatchedRoute']);

            // Laravel 5.2 event
            $this->events->listen(RouteMatched::class,
                function (RouteMatched $event) {
                    $this->handleMatchedRoute($event->route);
                });
        }

        return $next($request);
    }

    public function handleMatchedRoute($route){
        $routeInfo = new RouteInfo($route);

        response()->json([
            'data' => $routeInfo,
        ])->send();

        exit();
    }
}
