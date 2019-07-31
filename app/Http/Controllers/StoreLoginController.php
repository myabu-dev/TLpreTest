<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StoreLoginController extends Controller
{
    public function index (Request $request) 
    {
        $login_cookie = Cookie::get('login_cookie');
        $last_login = Cookie::get('last_login');

        // クッキーにログイン情報があれば自動ログインして、クッキーを更新
        if(!empty($login_cookie) && !empty($last_login))
        {
            $user_id = DB::table('store_users')->select('user_id')->where('login_cookie', '=', $login_cookie)
                    ->where('last_login', '=', $last_login)->value('user_id');

            $request->session()->regenerate();
            $request->session()->flush();
            $request->session()->put('user_id', $user_id);

            $last_login = date('Y-m-d h:i:s');
            Cookie::queue('last_login', $last_login , 60*24*7, '/store/login');
            DB::table('store_users')->where('user_id', $user_id)->update(['last_login' => $last_login]);
            return redirect('/');
        }
        return view('store_login');
    }

    
    public function login (Request $request) 
    {
        $user_id = $request->input('user_id');
        $password = $request->input('password');

        $db_password = DB::table('store_users')->select('password')->where('user_id', '=', $user_id)->value('password');
        //パスワード認証
        if (Hash::check($password, $db_password)) {
            $db_password = DB::table('store_users')->select('user_id')->where('user_id', '=', $user_id)->value('user_id');

            $request->session()->regenerate();
            $request->session()->flush();
            $request->session()->put('user_id', $user_id);

            $last_login = date('Y-m-d h:i:s');
            $login_cookie = NULL;
            //ログインを保持する場合
            if($request->input('keep_login') == 'on'){
                $login_cookie = openssl_random_pseudo_bytes(127);
                $login_cookie = bin2hex($login_cookie);
                Cookie::queue('login_cookie', $login_cookie, 60*24*7, '/store/login');
                Cookie::queue('last_login', $last_login , 60*24*7, '/store/login');
            }
            DB::table('store_users')->where('user_id', $user_id)->update(['last_login' => $last_login, 'login_cookie' => $login_cookie]);

            return redirect('/');
        }else{

            return redirect('/store/login')->with('flash_message', 'IDかパスワードが間違っています。');
        }
    }
}
