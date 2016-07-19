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
    | Route repository
    |--------------------------------------------------------------------------
    |
    | Specify class for RouteRepository.
    |
    */

    'repository' => \Asvae\ApiTester\DefaultRouteRepository::class,

    /*
    |--------------------------------------------------------------------------
    | Default route
    |--------------------------------------------------------------------------
    |
    | Define the route for api router.
    | http://your-site.com/[route]
    |
    */

    'route' => 'api-tester',

    /*
    |--------------------------------------------------------------------------
    | Filter routes
    |--------------------------------------------------------------------------
    | All your routes will be filtered via given patterns. Both include and
    | exclude are always applied. You can also use regex when needed.
    |
    | ## Examples
    |
    | ### Include all
    | 'include' => [[]]
    |
    | ### Include some routes
    | 'include' => [
    |      'api/users',
    |      'api/sales',
    |      // ...
    |  ]
    |
    | ### Include advanced
    | 'include' => [
    |      [
    |         'path' => 'api/v(1|2|3)/.*',
    |         'name' => '.*',
    |         'method' => '(GET|POST|PUT|PATCH|DELETE)'
    |      ],
    |      // ...
    |  ]
    |
    | ### Include all except 'users'
    | 'include' => [[]],
    | 'exclude' => ['api/users'],
    |
    */

    'include' => [[]],

    'exclude' => [],
];
