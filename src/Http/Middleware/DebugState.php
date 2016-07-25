<?php

namespace Asvae\ApiTester\Http\Middleware;

use Closure;

/**
 * Class DebugState
 *
 * @package \Asvae\ApiTester\Http\Middleware
 */
class DebugState
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!config('api-tester.enabled')) {
            return response('Api tester disabled', 403);
        }

        return $next($request);
    }
}
