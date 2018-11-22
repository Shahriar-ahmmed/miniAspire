<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Api!
|
*/
Route::post('login', 'Api\PassportController@login');

Route::group(['middleware' => 'auth:api','namespace' => 'Api'], function () {
    Route::resources(['client_users' => 'ClientUserController']);
    Route::resources(['accounts' => 'AccountController']);
    Route::resources(['loans' => 'LoanController']);
    Route::resources(['repayments' => 'RepaymentController']);

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
