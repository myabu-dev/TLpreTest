<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreTopController extends Controller
{
    //        
    public function index (Request $request) 
    {
        $user_id = $request->session()->get('user_id');
        //ログインしていないとき
        if(empty($user_id)){
            return redirect('/store/login');
        }
        return view('store_top')->with('user_id',$user_id);
    }
}
