<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\CartItem;
use App\Entity\Product;
use Cache;

class CartController extends Controller
{
    public function toCart(Request $request)
    {
        $access_token = Cache::get('access_token', '');
        echo $access_token;
        $cart_items = [];
        // 获取购物车cookie
        $bk_cart = $request->cookie('bk_cart');
        // 拆分购物车cookie 变为数组
        $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : []);
        // 判断是否登陆
        $member = $request->session()->get('member');
        if ($member != '') {
            // 用户已经登陆 同步数据 直接返回视图
            $cart_items = $this->syncCart($member->id, $bk_cart_arr);
            $data = [
                'cart_items' => $cart_items
            ];
            return view('cart', $data);
        }
        // 购物车逻辑
        foreach ($bk_cart_arr as $_key => $_value) {
            // 判断商品是否已经存在购物车
            $index = strpos($_value, ':');
            // 重组数组
            $cart_item = new CartItem();
            $cart_item->id = $_key;
            $cart_item->product_id = substr($_value, 0, $index);
            $cart_item->count = (int) substr($_value, $index + 1);
            $cart_item->product = Product::find($cart_item->product_id);
            if ($cart_item->product != null) {
                array_push($cart_items, $cart_item);
            }
        }
        // 填充数据
        $data = [
            'cart_items' => $cart_items
        ];
        return view('cart', $data);
    }

    private function syncCart($member_id, $bk_cart_arr)
    {
        // 获取用户数据库的购物车
        $cart_items = CartItem::where('member_id', '=', $member_id)->get();
        $cart_items_arr = array();
        foreach ($bk_cart_arr as $_key => $_value) {
            $index = strpos($_value, ':');
            $product_id = substr($_value, 0, $index);
            $count = ((int)substr($_value, $index + 1));
            // 判断购物车cookie 是否存在数据库里面
            $exist = false;
            foreach ($cart_items as $cart_temp) {
                if ($cart_temp->product_id == $product_id) {
                    // 存在判断数量是否一致
                    if ($cart_temp->count < $count) {
                        $cart_temp->count = $count;
                        $cart_temp->save();
                    }
                    $exist = true;
                    break;
                }
            }
            // 不存在数据就新增
            if ($exist == false) {
                $cart_item = new CartItem();
                $cart_item->member_id = $member_id;
                $cart_item->product_id = $product_id;
                $cart_item->count = $count;
                $cart_item->save();
                $cart_item->product = Product::find($cart_item->product_id);
                array_push($cart_items_arr, $cart_item);
            }
        }

        // 获取商品
        foreach ($cart_items as $cart_item) {
            $cart_item->product = Product::find($cart_item->product_id);
            array_push($cart_items_arr, $cart_item);
        }

        return $cart_items_arr;
    }
}
