<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>


    <div class="card-area">

    <div class="card">
    @if ($errors->any())
        <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
        </div>
    @endif
        <form action="/store/register/" method="post" class="form-signin" id="user_data_form">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="text" name="store_name" class="form-control input-form" placeholder="店名" value = "{{ old('store_name') }}">
            <input type="text" name="user_id" class="form-control input-form" placeholder="管理者ユーザID" value = "{{ old('user_id') }}">
            <input type="password" name="password" class="form-control input-form" placeholder="管理者パスワード">
            <input type="password" name="re_password" class="form-control input-form" placeholder="パスワードをもう一度入力">
            <div class="checkbox">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="keep_login" class="custom-control-input" id="keep_switch">
                    <label class="custom-control-label" for="keep_switch">ログインを保持する</label>
                </div>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">新規登録</button>
        </form>
            <a class="register" href="/login">すでに登録済みの方</a>
    </div>
    <div>
</body>
</html>
