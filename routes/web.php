<?php

use Illuminate\Http\Request;

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
    return redirect('/top');
});
Route::get('/top', function () {
    return view('top');
});;

Route::get('/store/top',  'StoreTopController@index');
Route::get('/store/login',  'StoreLoginController@index');
Route::get('/store/register', function () {
    return view('store_register');
});

Route::get('/store/logout', function (Request $request) {
    $request->session()->regenerate();
    $request->session()->flush();
    setcookie('login_cookie');
    setcookie('last_login');
    return redirect('/top');
});

Route::post('/store/login', 'StoreLoginController@login');
Route::post('/store/register', 'StoreRegisterController@registration');


// delete のルートがあるのに使えないので暫定の対策
Route::delete('/api/product/{product_id}','ProductApiController@destroy');
// api
Route::apiResource('/api/product','ProductApiController');


