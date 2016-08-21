<?php

// Api-tester base route. This is entry point for frontend-SPA.
Route::get('/', [
    'as'   => 'home',
    'uses' => 'HomeController@index',
]);

// Fetch all Laravel routes.
Route::post('routes/index', 'RouteController@index');

Route::post('requests/index', 'RequestController@index');
Route::post('requests/store', 'RequestController@store');
Route::post('requests/update', 'RequestController@update');
Route::post('requests/destroy', 'RequestController@destroy');


// We won't publish library's assets.
// Instead we'll pass them via app which is slower but fine for development.
Route::group(['prefix' => 'assets'], function () {

    $filePattern = '^([a-z0-9_\-\.]+)$';

    Route::get('fonts/{_file}', [
        'as' => 'image',
        'uses' => 'AssetsController@font'
    ])->where('_file', $filePattern);

    Route::get('img/{_file}', [
        'as' => 'image', 'uses' => 'AssetsController@image'
    ])->where('_file', $filePattern);

    Route::get('{_file}', [
        'as' => 'file',
        'uses' => 'AssetsController@index'
    ])->where('_file', $filePattern);
});

/**
 * This route is quite special as it prevents user from caching routes
 * while in development mode. Sorta fool-proof measure.
 *
 * How it works? Believe it or not, laravel won't allow you to cache
 * closure route. Hacky but works.
 * This route is debug only, hence in production
 * it isn't registered and route cache is allowed.
 */
Route::any('* routes should not be cached',[
    'as' => 'routes-should not be cached',
    'uses' =>  function () { return 'Api-tester routes-should not be cached';},
]);




