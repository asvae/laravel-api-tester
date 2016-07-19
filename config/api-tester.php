<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable api tester
    |--------------------------------------------------------------------------
    |
    | You can turn on and off the tester on will. Or maybe bind it to
    | some env value.
    |
    */

    'enabled' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define list of middleware(s), that should be used for api-tester.
    | CRSF token will be handled automatically.
    |
    */

    'middleware' => ['web'],


    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define list of middleware(s), that should be used for api-tester.
    | CRSF token will be handled automatically.
    |
    */
    'repository' => \Asvae\ApiTester\DefaultRouteRepository::class,

    /*
    |--------------------------------------------------------------------------
    | Matching pattern
    |--------------------------------------------------------------------------
    | Represent only routes that matching given pattern.
    |
    | Example:
    |
    | 'match' => [
    |     [
    |         'path' => '#api/v(1|2|3)/.*#',
    |         'name' => '#.*#',
              'method' => '#(GET|POST|PUT|PATCH|DELETE)#'
    |      ]
    ]  ]
    |
    */
    'match'      => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Except pattern
    |--------------------------------------------------------------------------
    | Except routes that matching given pattern.
    |
    */
    'except'     => [

    ],
];
