<?php

Route::get('api-tester', 'ApiTesterController@index');
Route::post('_api-tester/routes', 'ApiTesterController@routes');
Route::get('_api-tester/assets/{file}', 'ApiTesterController@assets');
Route::get('_api-tester/test-routes/{type}', 'ApiTesterController@testRoutes');
