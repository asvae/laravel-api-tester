<?php

Route::get(config('api-tester.route'), 'ApiTesterController@index');
Route::post('_api-tester/routes', 'ApiTesterController@routes');
Route::get('_api-tester/assets/{file}', 'AssetsController@index');
Route::get('_api-tester/test-routes/{type}', 'ApiTesterController@testRoutes');
