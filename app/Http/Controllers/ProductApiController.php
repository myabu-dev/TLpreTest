<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where_array = array();

        // storeの指定　userが指定されていればuserのstore storeが指定されていればstore優先
        if(!empty($request->user)||empty($request->store)){
            $store_id;
            if(!empty($request->user)){
                $store_id = DB::table('store_users')->select('store_id')->where('user_id', $request->user)->value('store_id');
            }
    
    
            if(!empty($request->store)){
                $store_id = $request->store;
            }

            if(!empty($store_id)){
                $store_where = array(
                    'own_products.store_id', '=' ,$store_id
                );
                $where_array[] = $store_where;
            }
        }

        //エラーリスト
        $error_reasons = array();

        // 価格上限の指定
        if(!empty($request->price_max)){
            if(is_numeric($request->price_max) == false){
                $error_reasons[] = '価格の上限には半角数字をいれてください。';
            }
            $price_max_where = array(
                'products.price', '<=' ,$request->price_max
            );
            $where_array[] = $price_max_where;
        }

        // 価格下限の指定
        if(!empty($request->price_min)){
            if(is_numeric($request->price_min) == false){
                $error_reasons[] = '価格の下限には半角数字をいれてください。';
            }
            $price_min_where = array(
                'products.price', '>=' ,$request->price_min
            );
            $where_array[] = $price_min_where;
        }

        // 名前の指定
        if(!empty($request->name)){
            $name_where = array(
                'products.name', 'like' , '%'.$request->name.'%'
            );
            $where_array[] = $name_where;
        }

        if(!empty($error_reasons)){
            return response()->json(
                [ 
                    'complete_flag' => false ,
                    'reasons' => $error_reasons,
                ]
            );
        }

        $product_list = DB::table('products')
            ->select('own_products.store_id', 'products.name as product_name', 'products.img_url', 'products.discription', 'products.price', 'products.id as product_id')
            ->leftJoin('own_products', 'products.id', '=', 'own_products.product_id')
            ->where($where_array)
            ->orderBy('products.created_at','desc')
            ->get();

        return response()->json(
            [ 
                'complete_flag' => true ,
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
                    'reasons' => ["user_id is not set"],
                ]
            );
        }

        // userの情報
        $user_data = DB::table('store_users')->select('id','store_id')->where('user_id', '=', $user_id)->first();
        $user_db_id = $user_data->id; 

        // エラーの配列
        $error_reasons = array();

        $product_name = trim($request->input('product_name'));
        if(empty($product_name)){
            $error_reasons[] = "商品名をいれてください。";
        }else if(mb_strlen($product_name, 'UTF-8') > 128){
            $error_reasons[] = "商品名が長過ぎます。";
        }

        $product_discription = trim($request->input('product_discription'));
        if(empty($product_discription)){
            $error_reasons[] = "商品説明をいれてください。";
        }else if(mb_strlen($product_name, 'UTF-8') > 500){
            $error_reasons[] = "商品説明が長過ぎます。";
        }

        $product_price = trim($request->input('product_price'));
        if(empty($product_price)){
            $error_reasons[] = "商品の価格をいれてください。";
        }else if(is_numeric($product_price) == false){
            $error_reasons[] = "商品の価格は半角数字をいれてください。";
        }

        if(!empty($request->file('img_file'))){
            $mime_type = $request->file('img_file')->getClientMimeType();
            if($mime_type!='image/jpeg' && $mime_type!='image/png'){
                $error_reasons[] = 'ファイルの種類にはjpeg,jpg,pngを指定してください。';
            }
            if($request->file('img_file')->getClientSize()/1024/1024 > 3){
                $error_reasons[] = 'ファイルの大きさは3MB以下にしてください';
            }
        }

        
        if(!empty($error_reasons)){
            return response()->json(
                [ 
                    'complete_flag' => false ,
                    'reasons' => $error_reasons,
                ]
            );
        }


        $img_url = NULL;
        if(!empty($request->file('img_file'))){
            $img_data = $request->file('img_file');
            $img_file_name = $img_data->store('public/product_img');
            $img_url = Storage::url($img_file_name);
        }

        $insert_product_data = array(
            'name' => $product_name,
            'img_url' => $img_url,
            'discription' => $product_discription,
            'price' => $product_price,
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
    public function destroy(Request $request, $product_id)
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
        // user id に紐つくstoreのid
        $user_store_id = DB::table('store_users')->select('store_id')->where('user_id', $user_id)->value('store_id');
        // productに紐つくstore_id
        $product_store_id = DB::table('products')->select('own_products.store_id')
            ->leftJoin('own_products', 'products.id', '=', 'own_products.product_id')
            ->where('products.id', $product_id)->value('own_products.store_id');

        // userのstoreが管理していない商品の時
        if($user_store_id != $product_store_id){
            return response()->json(
                [ 
                    'complete_flag' => false ,
                    'reason' => "this product is not your store's product",
                ]
            );
        }

        DB::table('products')->where('id', $product_id)->delete();
        DB::table('own_products')->where('product_id', $product_id)->delete();

        return response()->json(
            [ 'complete_flag' =>  true]
        );

    }
}
