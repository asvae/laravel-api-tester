<?php

// Base api-tester route. This is entry point for frontend-SPA.
Route::get('/', [
    'as'   => 'home',
    'uses' => 'HomeController@index'
]);


// Just index route. Because we can only get list of routes.
Route::resource('routes', 'RouteController', [
    'only' => ['index']
]);

// There we can get list of requests, store new request, update existing or destroy it.
// Route parameter renamed with leading underscore for no-collision with user's app.
Route::resource('requests', 'RequestController', [
    'only'       => ['index', 'store', 'update', 'destroy'],
    'parameters' => ['requests' => '_request']
]);

// Assets resources have no publish because this package just a service for developers.
// So, we can to route requests for files trough AssetsController.
// Route parameter named with leading underscore for no-collision with user's app.
Route::group(['prefix' => 'assets'], function () {

    // Define a file pattern
    $filePattern = '^([a-z_\-\.]+)$';

    Route::get('img/{_file}', ['as' => 'image', 'uses' => 'AssetsController@image'])
         ->where('_file', $filePattern);

    Route::get('{_file}', ['as' => 'file', 'uses' => 'AssetsController@index'])
         ->where('_file', $filePattern);
});


// I actually dunno what this route doing here... it isn't my.
Route::get('test-routes/{_type}', 'HomeController@testRoutes');






