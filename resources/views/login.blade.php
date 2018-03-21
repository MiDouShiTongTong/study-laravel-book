@extends('layouts.master')

@section('title', '登陆 ')

@section('content')
    <div class="weui-toptips weui-toptips_warn js_tooltips">错误提示</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">帐号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="name" placeholder="邮箱或手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password" name="password" placeholder="不少于6位"/>
            </div>
        </div>
        <div class="weui-cell weui-cell_vcode">
            <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="validate_code" placeholder="请输入验证码">
            </div>
            <div class="weui-cell__ft">
                <img class="weui-vcode-img validate_code" src="{{ url('/service/validate_code/create_code') }}">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <a class="weui-btn weui-btn_primary login" href="javascript:" id="showTooltips">确定</a>
        <a class="weui-btn weui-btn_plain-primary" href="{{ url('/register') }}">没有帐号? 去注册</a>
    </div>
    
    <!-- 隐藏字段 -->
    <input type="hidden" name="return-url" value="{{ $return_url }}">
@endsection

@section('js')
    <script src="{{ url('js/login.js') }}"></script>
@endsection