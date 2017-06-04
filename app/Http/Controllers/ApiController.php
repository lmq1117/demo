<?php
//项目中没用
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    use  AuthenticatesUsers;


    public function __construct()
    {
        $this->middleware('api');
    }

    //调用认证接口获取授权码
    protected function authenticateClient(Request $request)
    {
        $credentials = $this->credentials($request);

        $data = $request->all();

        $request->request->add([
            'grant_type'=>$data['grant_type'],
            'client_id'=>$data['client_id'],
            'client_secret'=>$data['client_secret'],
            'username'=>$credentials['phone'],
            'password'=>$credentials['password'],
            'scope'=>''
        ]);

        $proxy = Request::create('oauth/token','POST');

        $response = \Route::dispatch($proxy);

        return $response;
    }


    protected function authenticated(Request $request){
        return $this->authenticateClient();
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        return $this->authenticated($request);
    }



    protected function sendFailedLoginResponse(Request $request)
    {
        $msg = $request['errors'];
        $code = $request['code'];
        //????failed方法找不到咧  ???
        return $this->failed($msg,$code);
    }

    protected function failed($msg,$code){
        $res['code'] = $code;
        $res['msg'] = $msg;
        return json_encode($res);
    }
}
