<!doctype html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width,initial-scale=1" name=viewport>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/weui.css') }}">
    <link rel="stylesheet" href="{{ url('css/book.css') }}">
</head>
<body>

<!-- top menu -->
<div class="bk_title_bar">
    <div class="col-xs-1" id="history">
        <span class="glyphicon glyphicon-menu-left"></span>
    </div>
    <div class="col-xs-10 text-center">
        <span id="bk_title"></span>
    </div>
    <div class="col-xs-1 text-right" id="show-menu">
        <span class="glyphicon glyphicon-menu-hamburger"></span>
    </div>
</div>

<div class="page">
    @yield('content')
</div>

<!-- tooltip -->
<div class="bk_tooltip"><span></span></div>

<div>
    <div class="weui-mask" id="menuMask" style="display: none"></div>
    <div class="weui-actionsheet" id="menuActionsheet">
        <div class="weui-actionsheet__menu">
            <div class="weui-actionsheet__cell">主页</div>
            <div class="weui-actionsheet__cell">书籍类别</div>
            <div class="weui-actionsheet__cell">购物车</div>
            <div class="weui-actionsheet__cell">我的订单</div>
        </div>
        <div class="weui-actionsheet__action">
            <div class="weui-actionsheet__cell" id="menuActionsheetCancel">取消</div>
        </div>
    </div>
</div>

</body>
<script src="{{ url('js/jquery.min.js') }}"></script>
<script src="{{ url('js/base.js') }}"></script>
@yield('js')
</html>
