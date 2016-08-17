<?php

namespace Asvae\ApiTester\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRedirect
{
    const CATCH_REDIRECT = 'catch-redirect';

    public function handle(Request $request, Closure $next)
    {
        /**
         * @var \Symfony\Component\HttpFoundation\Response $response
         */
        $response = $next($request);

        // In case the request was sent by Api Tester and wanted catch-redirect
        // we will halt the response and output redirect information instead.
        if ($request->header('X-Api-Tester') === static::CATCH_REDIRECT) {
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

        return $next($request);
    }

}