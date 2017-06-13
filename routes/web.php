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

//生成菜单方法，不用时请注释掉
Route::get('/makemenu','WechatController@createMemu');

//验证微信URL时才开，不验证不开
//Route::any('/wechat','WechatController@verifyUrl');
Route::any('/wechat','WechatController@index');
//获取菜单
Route::get('/wechat/getmenu','WechatController@getMenu');

// sakya
Route::any('/visitor/getfrom','Api\VisitorController@getFrom');
Route::any('/visitor/sendcode','Api\VisitorController@sendCode');
Route::any('/company/getfrom','Api\CompanyController@getFrom');
Route::any('/company/sendcode','Api\CompanyController@sendCode');
Route::any('/visitor/test','Api\VisitorController@test');
Route::any('/company/test','Api\CompanyController@test');
Route::any('/visitor/getlist','Api\VisitorController@getList');
Route::any('/company/getlist','Api\CompanyController@getList');
Route::any('/visitor/updateone','Api\VisitorController@updateOne');
Route::any('/company/updateone','Api\CompanyController@updateOne');
Route::any('/visitor/delone','Api\VisitorController@delOne');
Route::any('/company/delone','Api\CompanyController@delOne');
Route::any('/visitor/getone','Api\VisitorController@getOne');
Route::any('/company/getone','Api\CompanyController@getOne');