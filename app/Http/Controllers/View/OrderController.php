<?php

namespace App\Http\Controllers\View;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderController extends Controller
{
    public function toOrderCommit(Request $request)
    {
        $product_ids = $request->input('product-ids');
        $product_ids_arr = ($product_ids != '' ? explode(',', $product_ids) : []);
        // 获取用户信息
        $member = $request->session()->get('member');
        // 创建新的订单
        // 获取提交的商品信息
        $cart_items = CartItem::where('member_id', '=', $member->id)->whereIn('product_id', $product_ids_arr)->get();
        // 获取商品属性
        $total_price = 0;
        $cart_items_arr = array();
        foreach ($cart_items as $_key => $_value) {
            $_value->product = Product::find($_value->product_id);
            $total_price += $_value->product->price * $_value->count;
            if ($_value->product != null) {
                array_push($cart_items_arr, $_value);
            }
        }

        // 填充数据视图
        $data = [
            'cart_items' => $cart_items_arr,
            'total_price' => $total_price
        ];
        return view('order_commit', $data);
    }

    public function toOrderList(Request $request)
    {
        // 获取订单信息
        $member = $request->session()->get('member');
        // 获取用户订单
        $orders = Order::where('member_id', $member->id)->get();
        foreach ($orders as $_key => $order) {
            $order_count = 0;
            // 获取订单数据
            $order_items = OrderItem::where('order_id', $order->id)->get();
            $order->order_items = $order_items;
            foreach ($order->order_items as $order_item) {
                // 获取订单数据 产品
                $order_item->product = json_decode($order_item->pdt_snapshot);
                $order_count +=  $order_item->count;
            }
            $order->count = $order_count;
        }
        $data = [
            'orders' => $orders
        ];
        return view('order_list', $data);
    }
}
