@extends('layouts.master')

@section('title', '订单提交')

@section('content')
    <div class="weui-cells__title order-title">所选商品</div>
    <div class="weui-cells">
        <!-- 显示商品列表 -->
        @foreach ($cart_items as $_key => $_value)
            <input type="hidden" name="product_id" class="product_id" value="{{ $_value->product->id }}">
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <div class="order-pdt-image">
                        <img class="order_commit_img" src="{{ $_value->product->preview }}" alt="{{ $_value->product->name }}">
                    </div>
                </div>
                <div class="weui-cell__bd order-pdt-name">
                    <p>{{ $_value->product->name }}</p>
                </div>
                <div class="weui-cell__ft col-xs-3 clear-padding order-desc">
                    <span class="order-pdt-price">{{ $_value->product->price }}</span>
                    &times;
                    <span class="order-pdt-count">{{ $_value->count }}</span>
                </div>
            </div>
        @endforeach
    </div>
    <!-- 支付方式 -->
    <div class="weui-cells__title order-title">支付方式</div>
    <div class="weui-cells">
        <div class="weui-cell weui-cell_select">
            <div class="weui-cell__bd">
                <select class="weui-select payway" name="payway">
                    <option selected="selected" value="1">支付宝</option>
                    <option value="2">微信</option>
                </select>
            </div>
        </div>
    </div>
    <!-- 总计 -->
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p class="price-count">总计 ：<span class="order-pdt-count-price pull-right clear-padding">¥ {{ $total_price }}</span></p>
            </div>
        </div>
    </div>
    <!-- 按钮 -->
    <div class="bk_fix_bottom">
        <div class="col-xs-12 text-center">
            <button class="pdt-btn weui-btn weui-btn_primary commit-order">
                提交订单
            </button>
        </div>
    </div>
    <!-- 数据 -->
    <form class="order-commit" action="/commit_order" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="payway_type">
        <input type="hidden" name="product_ids">
    </form>
@endsection

@section('js')
    <script src="{{ url('/js/order.js') }}"></script>
@endsection