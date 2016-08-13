<?php

namespace Asvae\ApiTester\Http\Middleware;

use Asvae\ApiTester\Entities\Route;
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
class DetectRouteMiddleware
{
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
        // In case the request was sent by Api Tester we will halt the request
        // and output route information instead.
        if ($request->hasHeader('X-Api-Tester')) {

            $this->events->listen(RouteMatched::class,
                function (RouteMatched $event) {
                    response()->json([
                        'data' => new RouteInfo($event->route),
                    ])->send();

                    exit();
                });
        }

        return $next($request);
    }
}
