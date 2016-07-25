<?php

define('API_TESTER_FILE_PATTERN', '^([a-z_\-\.]+?)$');

Route::resourceParameters(['requests' => 'request']);

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::resource('routes', 'RouteController', ['only' => ['index']]);

Route::resource('requests', 'RequestController', ['only' => ['index', 'store', 'update', 'delete']]);

Route::group(['prefix' => 'assets'], function () {
    Route::get('img/{file}', ['as'=> 'image', 'uses'=>'AssetsController@image'])->where('file', API_TESTER_FILE_PATTERN);
    Route::get('{file}', ['as' => 'file', 'uses'=>'AssetsController@index'])->where('file', API_TESTER_FILE_PATTERN);
});

Route::get('test-routes/{type}', 'HomeController@testRoutes');




