<?php
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




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test','Home\GoodsController@test');

Route::get('/stest','Home\GoodsController@sessionTest');
Route::get('/cadd','Home\GoodsCollectionController@goodsCollectionAdd');
Route::get('/nlist','Home\GoodsController@goodsNoticeList');
