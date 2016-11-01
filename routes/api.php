<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//Route::group(['prefix' => '/~ludovic.marchand/DigX/SP2/public'], function() {
//Route::group(['prefix' => '/nmbs_php/laravel/public/'], function() {
    //Route::group(['prefix' => 'api/v.1'], function() {
        Route::group(['prefix' => 'staff'], function()
        {
            Route::post('login', 'UserController@login');

            Route::group(['middleware' => 'auth:api'], function()
            {
                Route::get('/', 'UserController@index');
                Route::get('/{id}', 'UserController@byID');

                Route::post('login', 'UserController@login');
                Route::post('create', 'UserController@create');

                Route::Put('update/{id}', 'UserController@update');
                Route::Delete('delete/{id}', 'UserController@delete');
            });
        });
    //});
//});