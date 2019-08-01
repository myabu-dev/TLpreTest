<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where_array = array();

        // storeの指定
        if(!empty($request->store)){
            $store_where = array(
                'store_id', '=' ,$request->store
            );
            $where_array[] = $store_where;
        }

        // 価格上限の指定
        if(!empty($request->price_max)){
            $price_max_where = array(
                'price', '<=' ,$request->price_max
            );
            $where_array[] = $price_max_where;
        }

        // 価格下限の指定
        if(!empty($request->price_min)){
            $price_min_where = array(
                'price', '>=' ,$request->price_min
            );
            $where_array[] = $price_min_where;
        }

        // 名前の指定
        if(!empty($request->name)){
            $name_where = array(
                'name', 'like' , '%'.$request->name.'%'
            );
            $where_array[] = $name_where;
        }

        $product_list = DB::table('products')
            ->select('store_id', 'name as product_name', 'img_url', 'discription', 'price', 'created_at as create_time')
            ->where($where_array)
            ->orderBy('created_at','desc')
            ->get();

        return response()->json(
            [ 
                'product_list'=>$product_list,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        $user_id = $request->session()->get('user_id');
        if(empty($user_id)){
            return response()->json(
                [ 
                    'complete_flag' => false ,
                    'reason' => "user_id is not set",
                ]
            );
        }

        // userの情報
        $user_data = DB::table('store_users')->select('id','store_id')->where('user_id', '=', $user_id)->first();
        $user_db_id = $user_data->id;

        // ToDo　postデータバリデーション
        
        $img_file_name = NULL;
        if(!empty($request->file('img_file'))){
            $img_data = $request->file('img_file');
            //　拡張子
            $extension = $img_data->getClientOriginalExtension();
            // 一意な名前
            $unique_name = md5(unipid(rand(), true));
            $img_file_name = $request->file->store('public/product_img/'.$unique_name.'.'.$extension);
        }

        $insert_product_data = array(
            'store_id' => $user_data->store_id,
            'name' => $request->input('product_name'),
            'img_url' => $img_file_name,
            'discription' => $request->input('product_discription'),
            'price' => $request->input('product_price'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $product_id = DB::table('products')->insertGetId($insert_product_data);
        $insert_video_data = array();

        // ユーザデータ
        DB::table('own_products')->insert(
            [
            'store_id' => $user_data->store_id,
            'product_id' => $product_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        return response()->json(
            [ 'complete_flag' =>  true]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
