<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'/v1','middleware'=>['api']],function (){
    Route::post('/user/login','Api\LoginController@Login');
});

//Route::post('home/getstr2','Home\GoodsController@getstr2');

Route::group(['middleware'=>'cors'],function (){
    Route::post('getstr','Home\GoodsController@index');
    Route::post('getstr2','Home\GoodsController@getstr2');
    Route::post('/goods/goodslist','Home\GoodsController@getShopHomeGoods');


    //Route::post('/goods/goodslist','Home\GoodsController@getShopHomeGoods');
    Route::post('/goods/goodsinfo','Home\GoodsController@getGoodsDetail');
    Route::post('/goods/notice','Home\GoodsNoticeController@goodsNoticeAdd');//添加关注商品
    Route::post('/goods/collection','Home\GoodsCollectionController@goodsCollectionAdd');//添加收藏商品
    Route::post('/user/notice','Home\GoodsController@goodsNoticeList');//当前用户关注的商品列表
    Route::post('/user/collection','Home\GoodsController@goodsCollectionList');//当前用户收藏的商品列表
    Route::post('/order/all','Home\OrderController@getAllOrderList');//全部订单

    Route::post('/user/usercenter','Home\UserController@getUserInfo');//会员中心 会员信息

    //status
    //订单状态 0待付款，1已付款待发货，2已付款已发货，3已付款已收货，4客户申请退款，5客户申请退款并被审核，6退款完成 7未付款已取消
    Route::post('/order/status/orderlist','Home\OrderController@getStatusOrderList');//post传不同的type区分不同状态的订单

    //
    Route::post('/shoppingcart','Home\ShoppingCartController@addGoodsToShoppingCart');//购物车增删改查
    Route::post('/wechat/useropenid','WechatController@getOpenId');
    Route::post('/wechat/userinfo','WechatController@getUserInfo');
    Route::post('/user/addaddress','Home\AddressController@addAddress');//用户增加地址
    Route::post('/user/addresslist','Home\AddressController@addressList');//用户地址列表

    //-------------------
    //
    Route::post('/shoppingcart/commit','Home\OrderController@makeOrder');//从购物车中提交选定商品生成订单

    Route::post('/user/bind','UserController@bindPhone');//用户绑定手机
    //从购物车生成订单
    Route::post('/shoppingflow/createorder','Home\OrderController@makeOrder');

    //从订单号获取订单详情
    Route::post('/order/query','Home\OrderController@orderQuery');

    //以下
    //Route::post('/order/before_pay','Home\OrderController@getGoodsDetail');//待付款
    //Route::post('/order/before_shipping','Home\GoodsController@getGoodsDetail');//待发货
    //Route::post('/order/had_deliver','Home\GoodsController@getGoodsDetail');//待收货
    //Route::post('/order/had_complete','Home\GoodsController@getGoodsDetail');//已完成
    //购物车（添加、列表、删除商品）
    //我的购物记录
    //购物流程

    Route::post('/home/getstr','UserController@bindPhone');//用户绑定手机
    Route::post('/area/areainfo','Home\AddressController@getAreaInfo');//三级地址列表所需数据

    //Route::post('home/getstr','Home\GoodsController@index');


});


