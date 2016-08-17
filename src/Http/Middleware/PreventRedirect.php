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

        // Отлавливаем редиректы.
        if ($response->isRedirection()) {
            /**
             * @var \Symfony\Component\HttpFoundation\RedirectResponse $response
             */
            $response = response()->json(['data' => [
                'location' => $response->getTargetUrl(),
                'status' => $response->getStatusCode(),
            ]])->header('X-Api-Tester', 'redirect');
        }

        return $response;
    }

}