@extends('layouts.master')

@section('title', '购物车')

@section('content')
    <div class="weui-cells weui-cells_checkbox">
        @if ($cart_items != null)
            @foreach ($cart_items as $_key => $_value)
                <label class="weui-cell weui-check__label" for="{{ $_value->product_id }}">
                    <div class="weui-cell__hd">
                        <input type="checkbox" class="weui-check" value="{{ $_value->product_id }}" name="cart-item" id="{{ $_value->product_id }}">
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd pdt-wrapper">
                        <div class="pdt-content">
                            <div class="pdt-img">
                                <img src="{{ $_value->product->preview }}" alt="{{ $_value->product->name }}">
                            </div>
                            <div class="pdt-desc">
                                <h4 class="pdt-name">{{ $_value->product->name }}</h4>
                                <p class="pdt-num">数量：<span class="bk_count">{{ $_value->count }}</span></p>
                                <p class="pdt-count">总计：<span class="bk_price">{{ $_value->product->price * $_value->count }}</span></p>
                            </div>
                        </div>
                    </div>
                </label>
            @endforeach
        @else
            <div class="weui-cells__title text-center" style="margin-bottom: .77em;">
                购物车暂时是空的
            </div>
        @endif
    </div>

    <form class="order_commit" action="/order_commit" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="product-ids">
    </form>

    <!-- 底部菜单 按钮 -->
    <div class="bk_fix_bottom">
        <div class="col-xs-6 text-center">
            <button class="pdt-btn weui-btn weui-btn_primary charge-cart">
                结算
            </button>
        </div>
        <div class="col-xs-6 text-center">
            <button class="pdt-btn weui-btn weui-btn_default delete-cart">
                删除
            </button>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/cart.js') }}"></script>
@endsection