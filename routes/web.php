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

Route::middleware(['web',  'auth'])->group(function () {
    Route::namespace('Ammonkc\WpApi\Http\Controllers\Auth')
        ->prefix(\Config::get('wp-api.route_prefix', '/'))
        ->group(function () {
            Route::get('wpapi/auth', 'JwtAuthController@showLoginForm')->name('ammonkc/wpapi::jwt-auth');
            Route::post('wpapi/auth', 'JwtAuthController@login')->name('ammonkc/wpapi::login');
            Route::get('wpapi/logout', 'JwtAuthController@logout')->name('ammonkc/wpapi::logout');
        });
});
