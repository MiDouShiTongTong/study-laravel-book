<?php

namespace App\Http\Controllers\Service;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderController extends Controller
{
    public function OrderCommit(Request $request)
    {
        $product_ids = $request->input('product_ids');
        $request->input('product_ids');
        $product_ids_arr = ($product_ids != '' ? explode(',', $product_ids) : []);
        // 获取用户信息
        $member = $request->session()->get('member');
        // 获取购物商品信息
        $cart_items = CartItem::where('member_id', '=', $member->id)->whereIn('product_id', $product_ids_arr)->get();
        if ($cart_items != '[]' && !empty($cart_items)) {
            // 创建新的订单
            $order = new Order();
            $order->member_id = $member->id;
            $order->save();
            // 获取商品属性
            $total_price = 0;
            $name = '';
            $cart_items_arr = array();
            foreach ($cart_items as $_key => $cart_item) {
                $cart_item->product = Product::find($cart_item->product_id);
                $total_price += $cart_item->product->price * $cart_item->count;
                $name .= ('《' . $cart_item->product->name . '》');
                if ($cart_item->product != null) {
                    array_push($cart_items_arr, $cart_item);
                    // 创建订单item
                    $order_item = new OrderItem();
                    $order_item->order_id = $order->id;
                    $order_item->product_id = $cart_item->product_id;
                    $order_item->count = $cart_item->count;
                    $order_item->pdt_snapshot = json_encode($cart_item->product);
                    $order_item->save();
                }
            }
            // 订单商品名称
            $order->name = $name;
            $order->total_price = $total_price;
            $order->order_no = 'E' . time() . $order->id;
            $order->status = 2;
            $order->save();
            // 删除购物车数据
            CartItem::where('member_id', '=', $member->id)->delete();
        }
        // 支付
        return response(view('pay.alipay'))->cookie('bk_cart', null, -1);
    }
}
