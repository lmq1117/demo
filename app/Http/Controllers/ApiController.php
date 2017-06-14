<?php
//项目中没用
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\WechatController;

class ApiController extends Controller
{
    protected $returnMsg;
    protected $error_no = [
        0 => 'success',
        1 => '收藏商品失败！',
        2 => '关注商品失败！',
        3 => '同一商品不能重复关注！',
        4 => '同一商品不能重复收藏！',
        5 => '你已经评论过该商品！',
        6 => '已付款订单不能取消！',
    ];

    protected function setReturnMsg($code=0){
        $this->returnMsg['code'] = $code;
        $this->returnMsg['message'] = $this->error_no[$code];
    }

    //
    //use  AuthenticatesUsers;
    //
    //
    //public function __construct()
    //{
    //    $this->middleware('api');
    //}
    //
    ////调用认证接口获取授权码
    //protected function authenticateClient(Request $request)
    //{
    //    $credentials = $this->credentials($request);
    //
    //    $data = $request->all();
    //
    //    $request->request->add([
    //        'grant_type'=>$data['grant_type'],
    //        'client_id'=>$data['client_id'],
    //        'client_secret'=>$data['client_secret'],
    //        'username'=>$credentials['phone'],
    //        'password'=>$credentials['password'],
    //        'scope'=>''
    //    ]);
    //
    //    $proxy = Request::create('oauth/token','POST');
    //
    //    $response = \Route::dispatch($proxy);
    //
    //    return $response;
    //}
    //
    //
    //protected function authenticated(Request $request){
    //    return $this->authenticateClient();
    //}
    //
    //protected function sendLoginResponse(Request $request)
    //{
    //    $this->clearLoginAttempts($request);
    //    return $this->authenticated($request);
    //}
    //
    //
    //
    //protected function sendFailedLoginResponse(Request $request)
    //{
    //    $msg = $request['errors'];
    //    $code = $request['code'];
    //    //????failed方法找不到咧  ???
    //    return $this->failed($msg,$code);
    //}
    //
    //protected function failed($msg,$code){
    //    $res['code'] = $code;
    //    $res['msg'] = $msg;
    //    return json_encode($res);
    //}
}
