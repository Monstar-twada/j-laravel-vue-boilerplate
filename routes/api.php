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
Route::group(['middleware' => 'api'],function(){
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['prefix' => 'users'], function () {
        $c = 'Auth\LoginController';
        Route::post('authenticate', $c . '@authenticateAny');
        //Route::post('refresh', $c.'@refresh');
        Route::post('forgot-password', 'Auth\ForgotPasswordController@sendResetLink');
        Route::post('change-password', 'Auth\ResetPasswordController@resetPass');
        Route::post('register', 'Api\UserController@store');
    });

    Route::group(['prefix' => 'users'], function () {
        $c = 'Api\UserController';
        Route::post('', $c . '@store');
        Route::get('', $c . '@index');
        Route::get('all', $c . '@all');
        Route::get('{id}', $c . '@show');
        Route::delete('{id}', $c . '@destroy');
        Route::put('{id}', $c . '@update');
    });
    Route::group(['middleware' => 'jwt.auth'], function () { });
})
