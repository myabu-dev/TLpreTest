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

Route::get('/store/top',  'StoreTopController@index');
Route::get('/store/login',  'StoreLoginController@index');
Route::get('/store/register', function () {
    return view('store_register');
});

Route::post('/store/login', 'StoreLoginController@login');

Route::post('/store/register', 'StoreRegisterController@registration');