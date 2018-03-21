@extends('layouts.master')

@section('title', $product->name)

@section('content')
    <link rel="stylesheet" href="{{ url('/css/swipe.css') }}">

    <!-- 商品 轮播 图片 -->
    <div class="addWrap">
        <div class="swipe" id="mySwipe">
            <div class="swipe-wrap">
                @foreach ($pdt_images as $_key => $_value)
                    <div>
                        <a href="javascript:;"><img class="img-responsive" src="{{ $_value->image_path }}"></a>
                    </div>
                @endforeach
            </div>
        </div>
        <ul id="position">
            @foreach ($pdt_images as $_key => $_value)
                <li class="{{ $_key == 0 ? 'cur' : '' }}"></li>
            @endforeach
        </ul>
    </div>

    <!-- 商品 名称 描述 -->
    <div class="weui-cells__title">
        <span class="bk_title">{{ $product->name }}</span>
        <span class="bk_price pull-right">¥ {{ $product->price }}</span>
    </div>
    <div class="weui-cells">
        <div class="weui-cell">
            <p class="bk_summary">{{ $product->summary }}</p>
        </div>
    </div>

    <!-- 商品 内容 描述 -->
    <div class="weui-cells__title">详细介绍</div>
    <div class="weui-cells">
        <div class="weui-cell">
            <p>
                @if ($pdt_content != null)
                    {!! $pdt_content->content !!}
                @endif
            </p>
        </div>
    </div>

    <!-- 底部菜单 按钮 -->
    <div class="bk_fix_bottom">
        <div class="col-xs-6 text-center">
            <button class="pdt-btn weui-btn weui-btn_primary add-cart">
                加入购物车
            </button>
        </div>
        <div class="col-xs-6 text-center">
            <button class="pdt-btn weui-btn weui-btn_default to-cart">
                查看购物车
                (<span id="cart_num">{{ $cart_num }}</span>)
            </button>
        </div>
    </div>

    <!-- 商品隐藏字段 -->
    <input type="hidden" name="product_id" value="{{ $product->id }}">

@endsection

@section('js')
    <script src="{{ url('/js/swipe.js') }}"></script>
    <script src="{{ url('/js/product.js') }}"></script>
@endsection