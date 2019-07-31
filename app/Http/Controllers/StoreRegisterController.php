<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class StoreRegisterController extends Controller
{
    public function registration(Request $request)
    {
        $request->validate([
            //重複確認などのバリデーション
            'store_name' => 'required',
            'user_id' => 'required|unique:store_users,user_id|max:32',
            'password' => 'bail|required|min:8',
            're_password' => 'same:password',
        ]);

        $user_id = $request->input('user_id');
        $password = $request->input('password');
        $password = Hash::make($password);
        
        $login_cookie = NULL;
        $last_login = date('Y-m-d h:i:s');
        // ログインを保持するときは、クッキーを保存
        if($request->input('keep_login') == 'on')
        {
            $login_cookie = openssl_random_pseudo_bytes(127);
            $login_cookie = bin2hex($login_cookie);
            Cookie::queue('login_cookie', $login_cookie, 60*24*7, '/store/login');
            Cookie::queue('last_login', $last_login , 60*24*7, '/store/login');
        }

        // 店舗情報
        $insert_store_data = array(
            'name' => $request->input('store_name'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $store_id = DB::table('stores')->insertGetId($insert_store_data);

        // ユーザデータ
        DB::table('store_users')->insert(
            [
            'user_id' => $user_id,
            'store_id' => $store_id,
            'last_login' => $last_login ,
            'login_cookie' => $login_cookie,
            'password' => $password,
            'resister_date' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        //　ユーザのデータをセッションに保持
        $request->session()->regenerate();
        $request->session()->flush();
        $request->session()->put('user_id', $user_id);

        return redirect('/');
    }
}