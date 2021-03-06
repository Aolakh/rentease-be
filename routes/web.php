<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'cors'], function () {
    Route::get('/property','PropertyController@index');
    Route::get('/property/search','PropertyController@search');
    Route::post('/property/add','PropertyController@store');

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::get('/location/search','LocationController@searchLocations');
    // Route::get('/location/search','PropertyController@searchLocations');
});

Route::group(['middleware' =>  ['cors' , 'auth.jwt'] ], function () {
    Route::post('/property/add','PropertyController@store');
});
