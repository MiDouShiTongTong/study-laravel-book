<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\PdtContent;
use App\Entity\PdtImages;
use App\Entity\CartItem;

class BookController extends Controller
{
    public function toCategory()
    {
        $categorys = Category::where('parent_id', '=', 0)->get();
        $data = [
            'categorys' => $categorys
        ];
        return view('category', $data);
    }

    public function toProduct($category_id)
    {
        $products = Product::where('category_id', '=', $category_id)->get();
        $data = [
            'products' => $products
        ];
        return view('product', $data);
    }

    public function toPtdContent(Request $request, $product_id)
    {
        $product = Product::find($product_id);
        // 获取商品详情
        $pdt_content = PdtContent::where('product_id', '=', $product_id)->first();
        // 获取商品图片
        $pdt_images = PdtImages::where('product_id', '=', $product_id)->get();
        // 获取商品购物车数量
        // 如果登陆 读取数据库的信息
        $member = $request->session()->get('member', '');
        $count = 0;
        if ($member != '') {
            $cart_item = CartItem::where('product_id', '=', $product_id)->get();
            foreach ($cart_item as $_key => $_value) {
                if ($_value->product_id == $product_id) {
                    $count = $_value->count;
                    if ($count == '') {
                        $count = 0;
                    }
                    break;
                }
            }
        } else {
            $bk_cart = $request->cookie('bk_cart');
            $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : []);
            // 购物车逻辑
            foreach ($bk_cart_arr as $_key => $_value) {
                $index = strpos($_value, ':');
                // 获取等于当前商品Id的购物车输了
                if (substr($_value, 0, $index) == $product_id) {
                    $count = ((int) substr($_value, $index + 1));
                }
                break;
            }
        }

        // 填充数据
        $data = [
            'product'       => $product,
            'pdt_content'   => $pdt_content,
            'pdt_images'    => $pdt_images,
            'cart_num'      => $count
        ];
        return view('pdt_content', $data);
    }
}
