<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/**
 * View Route
 *
 */
// master
Route::get('/', function () {
    return view('welcome');
});
// register
Route::get('/register', 'View\MemberController@toRegister');
// login
Route::get('/login', 'View\MemberController@toLogin');
// categoryList
Route::get('/category', 'View\BookController@toCategory');
// pdtList
Route::get('/product/category_id/{category_id}', 'View\BookController@toProduct');
// pdtContent
Route::get('/product/{product_id}', 'View\BookController@toPtdContent');
// cart
Route::get('/cart', 'View\CartController@toCart');
// pay
Route::get('/pay', function () {
    return view('alipay');
});

/**
 * Service Route
 *
 */
Route::group(['prefix' => 'service'], function () {
    // validateCode
    Route::get('validate_code/create_code', 'Service\ValidateController@createCode');
    // sendEmail
    Route::get('validate_email', 'Service\ValidateController@validateEmail');
    // sendSMS
    Route::post('validate_phone/send_sms', 'Service\ValidateController@sendSMS');
    // register
    Route::post('register', 'Service\MemberController@register');
    // login
    Route::post('login', 'Service\MemberController@login');
    // getChildCategory
    Route::get('category/parent_id/{parent_id}', 'Service\BookController@getCategoryByParentId');
    // dadCart
    Route::get('cart/add/{product_id}', 'Service\CartController@addCart');
    // deleteCart
    Route::get('cart/delete', 'Service\CartController@deleteCart');

    // pay
    Route::post('alipay', 'Service\PayController@aliPay');
    Route::post('wxpay', 'Service\PayController@wxPay');
    // payNotify
    Route::post('pay/ali_notify', 'Service\PayController@aliNotify');
    Route::post('pay/wx_notify', 'Service\PayController@wxNotify');
    // payCallBack
    Route::get('pay/ali_result', 'Service\PayController@aliResult');
    // payMerchant
    Route::get('pay/ali_merchant', 'Service\PayController@aliMerchant');
    // upload
    Route::post('upload/{type}', 'Service\UploadController@uploadFile');
});

// 中间件
Route::group(['middleware' => ['check.login']], function () {
    Route::post('order_commit', 'View\OrderController@toOrderCommit');
    Route::post('commit_order', 'Service\OrderController@OrderCommit');
    Route::get('order_list', 'View\OrderController@toOrderList');
});

// 后台
Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'service'], function () {
        Route::post('login', 'Admin\IndexController@login');
        Route::post('category/add', 'Admin\CategoryController@categoryAdd');
        Route::post('category/del', 'Admin\CategoryController@categoryDel');
        Route::post('category/edit', 'Admin\CategoryController@categoryEdit');
        Route::post('product/add', 'Admin\ProductController@productAdd');
        Route::post('product/del', 'Admin\ProductController@productDel');
        Route::post('product/edit', 'Admin\ProductController@productEdit');
    });
    Route::get('login', 'Admin\IndexController@toLogin');
    Route::get('index', 'Admin\IndexController@toIndex');
    Route::get('category', 'Admin\CategoryController@toCategory');
    Route::get('category/add', 'Admin\CategoryController@toCategoryAdd');
    Route::get('category/edit/{category_id}', 'Admin\CategoryController@toCategoryEdit');
    Route::get('product', 'Admin\ProductController@toProduct');
    Route::get('product_info', 'Admin\ProductController@toProductInfo');
    Route::get('product_add', 'Admin\ProductController@toProductAdd');
    Route::get('product_edit', 'Admin\ProductController@toProductEdit');
});