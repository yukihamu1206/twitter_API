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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/tweets', 'HomeController@index')->name('home');

Route::get('login/twitter', 'Auth\LoginController@redirectToTwitterProvider')->name('twitter.login');
Route::get('login/twitter/callback', 'Auth\LoginController@handleTwitterProviderCallback');
Route::get('auth/twitter/logout', 'Auth\LoginController@logout')->name('twitter.logout');
