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
Route::post('/goods/goodslist','Home\GoodsController@getShopHomeGoods');
Route::post('/goods/goodsinfo','Home\GoodsController@getGoodsDetail');
Route::post('/user/notice','Home\GoodsController@getGoodsDetail');//关注
Route::post('/user/collection','Home\GoodsController@getGoodsDetail');//收藏
Route::post('/user/userinfo','Home\GoodsController@getGoodsDetail');
Route::post('/order/all','Home\GoodsController@getGoodsDetail');//全部订单
Route::post('/order/bf_pay','Home\GoodsController@getGoodsDetail');//待付款
Route::post('/order/bf_ship','Home\GoodsController@getGoodsDetail');//待发货
Route::post('/order/bf_de','Home\GoodsController@getGoodsDetail');//待收货
Route::post('/order/xxxprefect','Home\GoodsController@getGoodsDetail');//已完成
//购物车（添加、列表、删除商品）
//我的购物记录
//购物流程


