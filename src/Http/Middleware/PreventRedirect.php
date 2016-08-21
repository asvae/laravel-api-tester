<?php

namespace Asvae\ApiTester\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRedirect
{
    const CATCH_REDIRECT_HEADER = 'catch-redirect';

    public function handle(Request $request, Closure $next)
    {
        /**
         * @var \Symfony\Component\HttpFoundation\Response $response
         */
        $response = $next($request);

        // In case the request was sent by Api Tester
        // and catching request is wanted
        // we will halt the response and output redirect information instead.
        if ($request->header('X-Api-Tester') === static::CATCH_REDIRECT_HEADER) {
            if ($response->isRedirection()) {
                /**
                 * @var \Symfony\Component\HttpFoundation\RedirectResponse $response
                 */
                return response()->json(['data' => [
                    'location' => $response->getTargetUrl(),
                    'status' => $response->getStatusCode(),
                ]])->header('X-Api-Tester', 'redirect');
            }
        }

        return $response;
    }

}