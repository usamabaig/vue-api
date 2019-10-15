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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('store/user/ad_account' , 'API\UserAdAccountController@store');
Route::post('get/user/ad_account' , 'API\UserAdAccountController@getUserAccountList');
Route::post('update/user/ad_account' , 'API\UserAdAccountController@updateUserAdAcount');



Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('user', 'API\UserController@details');
    Route::post('update/user', 'API\UserController@updateDetails');
    Route::post('update/password', 'API\UserController@updatePassowrd');
});