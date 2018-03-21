@extends('layouts.master')

@section('title', '订单列表')

@section('content')
    <!-- 显示商品列表 -->
    @foreach ($orders as $key => $order)
        <div class="order-list-group-item">
            <div class="weui-cells__title order-title">
                <span>订单号：{{ $order->order_no }}</span>
                @if ($order->status == 1)
                    <span class="bk_price pull-right">未支付</span>
                @else
                    <span class="bk_success pull-right">已付款</span>
                @endif
            </div>
            <div class="weui-cells">
                @foreach ($order->order_items as $order_item)
                    <div class="weui-cell">
                        <div class="weui-cell__hd">
                            <div class="order-pdt-image">
                                <img class="order_commit_img" src="{{ $order_item->product->preview }}" alt="{{ $order_item->product->name }}">
                            </div>
                        </div>
                        <div class="weui-cell__bd order-pdt-name">
                            <p>{{ $order_item->product->name }}</p>
                        </div>
                        <div class="weui-cell__ft col-xs-3 clear-padding order-desc">
                            <span class="order-pdt-price">{{ $order_item->product->price }}</span>
                            ×
                            <span class="order-pdt-count">{{ $order_item->count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="weui-cells__tips text-right">共计 {{ $order->count }} 件 合计： {{ $order->total_price }}</div>
        </div>
    @endforeach
@endsection

@section('js')

@endsection