<?php

namespace App\Http\Controllers\Service;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\CartItem;
use App\Models\MResult;

class CartController extends Controller
{
    public function addCart(Request $request, $product_id)
    {
        // 返回信息内容
        $m_result = new MResult();
        $m_result->status = 0;
        $m_result->message = '添加成功';

        // 如果当前已经登陆 -------------------------------------------------------------------------------------
        $member = $request->session()->get('member', '');
        if ($member != '') {
            $cart_items = CartItem::where('member_id', $member->id)->get();
            $exist = false;
            foreach ($cart_items as $cart_item) {
                if ($cart_item->product_id == $product_id) {
                    $cart_item->count ++;
                    $cart_item->save();
                    $exist = true;
                    break;
                }
            }
            if ($exist == false) {
                $cart_item = new CartItem();
                $cart_item->member_id = $member->id;
                $cart_item->product_id = $product_id;
                $cart_item->count = 1;
                $cart_item->save();
            }

        }
        // 未登录          -------------------------------------------------------------------------------------
        // 获取购物车cookie
        $bk_cart = $request->cookie('bk_cart');
        // 拆分购物车cookie 变为数组
        $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : []);
        // 默认商品个数
        $count = 1;
        // 购物车逻辑
        foreach ($bk_cart_arr as $_key => &$_value) {
            // 判断商品是否已经存在购物车
            $index = strpos($_value, ':');
            if (substr($_value, 0, $index) == $product_id) {
                // 如果找到商品 + 1
                $count = ((int) substr($_value, $index + 1) + 1);
                // 基本成员函数 引用重新设置数组
                $_value = $product_id.':'.$count;
                break;
            }
        }
        // 如果购物车不存在此商品
        if ($count == 1) {
            array_push($bk_cart_arr, $product_id.':'.$count);
        }
        
        return Response($m_result->toJson())->cookie('bk_cart', implode(',', $bk_cart_arr));
    }

    public function deleteCart(Request $request)
    {
        $m_result = new MResult();
        // 返回数据信息
        $m_result->status = 0;
        $m_result->message = '删除成功';
        // 获取需要删除
        $product_ids = $request->input('product_ids', '');
        if ($product_ids == '') {
            $m_result->status = 1;
            $m_result->message = '删除失败';
            $m_result->toJson();
        }
        // 拆分数组
        $product_ids_arr = explode(',', $product_ids);
        // 如果当前已经登陆 -------------------------------------------------------------------------------------
        $member = $request->session()->get('member', '');
        if ($member != '') {
            CartItem::whereIn('product_id', $product_ids_arr)->delete();
        }
        // 未登录          -------------------------------------------------------------------------------------
        // 获取购物车cookie
        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : []);
        // 把get过来的数据与cookie进行对比 如果存在就删除
        foreach ($bk_cart_arr as $_key => $_value) {
            $index = strpos($_value, ':');
            $product_id = substr($_value, 0, $index);
            if (in_array($product_id, $product_ids_arr)) array_splice($bk_cart_arr, $_key, 1, '');
        }
        // 排除空的数组
        $bk_cookie_cart_arr = array();
        foreach ($bk_cart_arr as $_key => $_value) {
            if (!empty($_value)) {
                $bk_cookie_cart_arr[] = $_value;
            }
        }

        return response($m_result->toJson())->cookie('bk_cart', implode(',', $bk_cookie_cart_arr));
    }
}
