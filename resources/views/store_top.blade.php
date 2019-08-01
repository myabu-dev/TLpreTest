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
    <h2>新たな商品を登録する</h2>
    <hr>
    <form method="POST" action="/upload" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="formGroupExampleInput" name="article_title" placeholder="タイトル...">
            </div>
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">価格</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="formGroupExampleInput" name="article_title" placeholder="タイトル...">
            </div>
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">商品画像</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="upfile" accept=".md, .html">
                        <label class="custom-file-label" for="customFile">ファイル選択...</label>
                    </div>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary reset">Cancel</button>
                    </div>
                </div>            
            </div>
        </div>
        <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label">商品説明</label>
            <div class="col-sm-10">
                <div class="form-group green-border-focus">
                    <textarea class="form-control" id="exampleFormControlTextarea5" rows="3"></textarea>
                </div>            
            </div>
        </div>

        <hr>
        <div class="row justify-content-center">
        <button type="submit"  class="btn btn-secondary btn-lg">商品を登録</button>
        </div>

    </form>
    <p>すでに登録されている商品</p>
    
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
        <tbody>

        </tbody>
    </table>
</div>
</body>
</html>
