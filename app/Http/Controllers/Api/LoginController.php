<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;
use Validator;

class LoginController extends ApiController
{
    //登录用户名标识为phone字段
    public function username(){
        return 'phone';
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'phone'=>'required|exists:users',
            'password'=>'required|between:6,32'
        ]);

        if($validator->fails()){
            $request->request->add([
                'errors'=>$validator->errors()->ToArray(),
                'code'=>401
            ]);
            return $this->sendFailedLoginResponse($request);
        }

        $credentials = $this->credentials($request);

        if($this->guard('api')->api->attempt($credentials,$request->has('remember'))){
            return $this->sendLoginResponse($request);
        }

        return $this->failed('login failed',401);
    }

}
