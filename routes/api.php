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

//Route::post('/goods/one','Home\GoodsController@getOne');
//啥都不用
Route::post('/goods/goodslist','Home\GoodsController@getShopHomeGoods');
Route::post('/goods/goodsinfo','Home\GoodsController@getGoodsDetail');
Route::post('/goods/notice','Home\GoodsNoticeController@goodsNoticeAdd');//添加关注商品
Route::post('/goods/collection','Home\GoodsCollectionController@goodsCollectionAdd');//添加收藏商品
Route::post('/user/notice','Home\GoodsController@goodsNoticeList');//当前用户关注的商品列表
Route::post('/user/collection','Home\GoodsController@goodsCollectionList');//当前用户收藏的商品列表
Route::post('/order/all','Home\OrderController@getAllOrderList');//全部订单

Route::post('/user/usercenter','Home\UserController@getUserInfo');//会员中心 会员信息


Route::post('/order/status/orderlist','Home\OrderController@getStatusOrderList');//post传不同的type区分不同状态的订单

//以下
//Route::post('/order/before_pay','Home\OrderController@getGoodsDetail');//待付款
//Route::post('/order/before_shipping','Home\GoodsController@getGoodsDetail');//待发货
//Route::post('/order/had_deliver','Home\GoodsController@getGoodsDetail');//待收货
//Route::post('/order/had_complete','Home\GoodsController@getGoodsDetail');//已完成
//购物车（添加、列表、删除商品）
//我的购物记录
//购物流程


