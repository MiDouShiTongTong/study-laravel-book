@extends('layouts.master')

@section('title', '注册')

@section('content')
    <div class="weui-toptips weui-toptips_warn js_tooltips">错误提示</div>
    <div class="weui-cells">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <!-- 表单方式选择 -->
        <div class="weui-cells__title">注册方式</div>
        <div class="weui-cells weui-cells_radio">
            <label class="weui-cell weui-check__label" for="phone">
                <div class="weui-cell__bd">
                    <p>手机号注册</p>
                </div>
                <div class="weui-cell__ft">
                    <input type="radio" class="weui-check" name="register_type" id="phone" checked="checked">
                    <span class="weui-icon-checked"></span>
                </div>
            </label>
            <label class="weui-cell weui-check__label" for="email">
                <div class="weui-cell__bd">
                    <p>邮箱注册</p>
                </div>
                <div class="weui-cell__ft">
                    <input type="radio" name="register_type" class="weui-check" id="email">
                    <span class="weui-icon-checked"></span>
                </div>
            </label>
        </div>
        <!-- 手机注册表单 -->
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" pattern="[0-9]*" name="phone">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label for="" class="weui-label">密码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="password" value="" placeholder="不得小于6位" name="password_phone">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label for="" class="weui-label">确认密码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="password" value="" placeholder="不得小于6位" name="password_phone_confirm">
                </div>
            </div>
            <div class="weui-cell weui-cell_vcode">
                <div class="weui-cell__hd"><label class="weui-label">手机验证码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="请输入验证码" name="code_phone">
                </div>
                <div class="weui-cell__ft">
                    <a href="javascript:;" class="weui-vcode-btn phone-code-send" style="width: 139.6px;padding-left: 0;padding-right: 0;text-align: center;position: relative;overflow: hidden;">获取验证码</a>
                </div>
            </div>
        </div>
        <!-- 邮箱注册表单 -->
        <div class="weui-cells weui-cells_form" style="display: none;">
            <div class="weui-cell weui-">
                <div class="weui-cell__hd"><label class="weui-label">邮箱</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="email">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label for="" class="weui-label">密码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="password" value="" placeholder="不得小于6位" name="password_email">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label for="" class="weui-label">确认密码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="password" value="" placeholder="不得小于6位" name="password_email_confirm">
                </div>
            </div>
            <div class="weui-cell weui-cell_vcode">
                <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="验证码" name="validate_code">
                </div>
                <div class="weui-cell__ft">
                    <img class="weui-vcode-img validate_code" src="{{ url('/service/validate_code/create_code') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary register" href="javascript:" id="showTooltips">确定</a>
        <a class="weui-btn weui-btn_plain-primary" href="{{ url('/login') }}">已有帐号? 请登录</a>
    </div>
@endsection

@section('js')
    <script src="{{ url('js/register.js') }}"></script>
@endsection