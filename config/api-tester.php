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
    | Default route
    |--------------------------------------------------------------------------
    |
    | Define the route for api router.
    | http://your-site.com/{route}
    |
    */

    'route' => 'api-tester',


    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define list of middleware, that should be used for api-tester.
    | CRSF token will be handled automatically.
    |
    */

    'middleware' => ['web'],


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
    | 'include' => []
    |
    | ### Include some routes
    | 'include' => [
    |      'api/users',
    |      'api/sales',
    |      // ...
    |  ]
    |
    | ### Include/exclude advanced syntax
    | 'include' => [
    |      [
    |         'path' => 'api/v(1|2|3)/.*',
    |         'name' => '.*',
    |         'method' => '(GET|POST|PUT|PATCH|DELETE)'
    |      ],
    |      // ...
    |  ]
    |
    | ### Include all except 'api/users'
    | 'include' => [],
    | 'exclude' => ['api/users'],
    |
    */

    'include' => '.*',
    'exclude' => null,


    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    |
    | Specify list of Route Repositories that to be used for providing routes.
    |
    */

    'route_repositories' => [
        Asvae\ApiTester\Repositories\RouteLaravelRepository::class,
        //Asvae\ApiTester\Repositories\RouteDingoRepository::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Request Repository
    |--------------------------------------------------------------------------
    | Define class of request repository.
    |
    */

    'request_repository' => Asvae\ApiTester\Repositories\RequestRepository::class,


    /*
    |--------------------------------------------------------------------------
    | Asvae\ApiTester\Repositories\RequestRepository configuration
    |--------------------------------------------------------------------------
    | The following lines are actual only for default Asvae\ApiTester\Repositories\RequestRepository
    | or similar implementations.
    |
    */


    'storage_driver' => 'file',

    'storage_drivers' => [
        'file' => [
            'class' => Asvae\ApiTester\Storages\JsonStorage::class,
            'options' => [
                'path' => 'storage/api-tester/requests.db'
            ]
        ],

        'firebase' => [
            'class' => Asvae\ApiTester\Storages\FireBaseStorage::class,
            'options' => [
                'base' => env('API_TESTER_FIREBASE_ADDRESS', 'https://example.firebaseio.com/api-tester/'),
                'secret' => env('API_TESTER_FIREBASE_SECRET', '<your-secret-api-key>'),
            ],
        ]
    ]
];
