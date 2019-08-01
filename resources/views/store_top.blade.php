<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script  type="text/javascript" src="{{ asset('js/store_top.js')}}"></script>
</head>

<body>

<div class="container">
    <p>ログイン中のユーザ: {{$user_id}}</p>
    <input type="hidden" id="user_id" value="{{$user_id}}">
    <hr>
    <h2>新たな商品を登録する</h2>
    <form method="POST" id="new_product" accept-charset="utf-8" return false>
        <input type="hidden" id='csrf' value="{{csrf_token()}}"/>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">商品名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_name" placeholder="タイトル...">
            </div>
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">価格</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_price" placeholder="タイトル...">
            </div>
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">商品画像</label>
        <input type="file" id="productImg" accept=".png, .jpg, .jpeg">
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">商品説明</label>
            <div class="col-sm-10">
                <div class="form-group green-border-focus">
                    <textarea class="form-control" id="product_discription" rows="3"></textarea>
                </div>            
            </div>
        </div>
    </form>

        <hr>
        <div class="row justify-content-center">
        <button id='register_product_btn'  class="btn btn-secondary btn-lg">商品を登録</button>
        </div>

    <p>すでに登録されている商品</p>
    <button id='reload_product_btn'  class="btn btn-primary" onclick="showStoreProducts()">リストを更新</button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>
                    画像
                </th>
                <th>
                    商品名
                </th>
                <th>
                    価格
                </th>
                <th>
                    説明
                </th>
                <th>
                    操作
                </th>
            </tr>
        </thead>
        <tbody id="product_list">

        </tbody>
    </table>
</div>
</body>
</html>
