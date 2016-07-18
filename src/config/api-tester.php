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
];