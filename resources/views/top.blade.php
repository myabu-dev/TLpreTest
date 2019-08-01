<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>TOP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script  type="text/javascript" src="{{ asset('js/top.js')}}"></script>
</head>

<body>

<div class="container">
    <h2>商品を検索絞り込む</h2>            
    <a href="/store/top">店舗ユーザログイン</a>
    <ul id="errors" >
    </ul>
    <form method="POST" id="serch_product" accept-charset="utf-8" return false>
        <input type="hidden" id='csrf' value="{{csrf_token()}}"/>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">商品名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_name" placeholder="商品名">
            </div>
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">価格下限</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_price_min" placeholder="">
            </div>
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">価格上限</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_price_max" placeholder="">
            </div>
        </div>
    </form>
    <button class="btn btn-secondary btn" onclick="clearSerchForm()">検索条件をクリア</button>

    <hr>
    <div class="row justify-content-center">
    <button id='search_product_btn'  class="btn btn-primary btn-lg" onclick="showStoreProducts()">検索</button>
    </div>

    <h3>商品リスト</h3>
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
            </tr>
        </thead>
        <tbody id="product_list">

        </tbody>
    </table>
</div>
</body>
</html>
