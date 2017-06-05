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

//验证微信URL时才开，不验证不开
//Route::any('/wechat','WechatController@verifyUrl');
Route::any('/wechat','WechatController@index');

// sakya
Route::any('/visitor/getfrom','Api\VisitorController@getFrom');
Route::any('/visitor/sendcode','Api\VisitorController@sendCode');
Route::any('/company/getfrom','Api\CompanyController@getFrom');
Route::any('/company/sendcode','Api\CompanyController@sendCode');
Route::any('/visitor/test','Api\VisitorController@test');
Route::any('/company/test','Api\CompanyController@test');