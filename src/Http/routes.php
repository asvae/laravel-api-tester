<?php

// Api-tester base route. This is entry point for frontend-SPA.
Route::get('/', [
    'as'   => 'home',
    'uses' => 'HomeController@index'
]);


// The only route for "routes" is index.
// Because we won't do anything but ask for the list.
Route::resource('routes', 'RouteController', [
    'only' => ['index']
]);

// "_" used to prevent collisions with your app.
Route::resource('requests', 'RequestController', [
    'only'       => ['index', 'store', 'update', 'destroy'],
    'parameters' => ['requests' => '_request']
]);

// We won't publish library's assets.
// Instead we'll pass them via app which is slower but fine for development.
Route::group(['prefix' => 'assets'], function () {

    $filePattern = '^([a-z_\-\.]+)$';

    Route::get('img/{_file}', ['as' => 'image', 'uses' => 'AssetsController@image'])
         ->where('_file', $filePattern);

    Route::get('{_file}', ['as' => 'file', 'uses' => 'AssetsController@index'])
         ->where('_file', $filePattern);
});


// Throw in some routes to test api-tester.
Route::get('test-routes/{_type}', 'HomeController@testRoutes');

/**
 * This route is quite special as it prevents user from caching routes
 * while in development mode. Sorta fool-proof measure.
 *
 * How it works? Believe it or not, laravel won't allow you to cache
 * closure route. Hacky but works.
 * This route is debug only, hence in production
 * it isn't registered and route cache is allowed.
 */
Route::any('* routes should not be cached', function () {});




