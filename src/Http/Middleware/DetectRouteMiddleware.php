<?php

namespace Asvae\ApiTester\Http\Middleware;

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
        if($request->hasHeader('X-Test-Request')){

            $this->events->listen(RouteMatched::class, function(RouteMatched $event){

                $route = $event->route;

                response()->json([
                    'domain'  => $route->domain(),
                    'name'    => $route->getName(),
                    'methods' => $route->getMethods(),
                    'path'    => $route->getPath(),
                    'action'  => $route->getAction(),
                ])->send();

                exit();
            });
        }


        return $next($request);
    }
}
