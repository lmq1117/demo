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
Route::post('/goods/home_goods','Home\GoodsController@getShopHomeGoods');
Route::post('/goods/detail','Home\GoodsController@getGoodsDetail');
