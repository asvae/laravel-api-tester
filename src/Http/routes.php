<?php

Route::resourceParameters(['requests' => 'request']);

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::resource('routes', 'RouteController', ['only' => ['index']]);

Route::resource('requests', 'RequestController',
    ['only' => ['index', 'store', 'update', 'destroy']]
);

Route::group(['prefix' => 'assets'], function () {
    $filePattern = '^([a-z_\-\.]+?)$';
    Route::get('img/{file}', ['as' => 'image', 'uses' => 'AssetsController@image'])
        ->where('file', $filePattern);
    Route::get('{file}', ['as' => 'file', 'uses' => 'AssetsController@index'])
        ->where('file', $filePattern);
});

Route::get('test-routes/{type}', 'HomeController@testRoutes');




