<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Home\SakyaController;
use Illuminate\Http\Request;
use App\Entity\Home\VisitorInfo;
use \Illuminate\Database\QueryException;

class VisitorController extends SakyaController
{   
    private $short_code_test = '123456';
    
    public function test(){
        return phpinfo();
    }
    // 接收前端提交的表单的
    public function getFrom(Request $request){
        //visitor[name]=xuteng&visitor[phone]=15549494949&visitor[scene]=7&visitor[note]=terqrqwrqwrqwrqwrwqrwrqqwrqwrqwrqwrqwrqw&short_code=123456
//        var_dump($request->isMethod('POST'));exit;
            // 1. 控制器验证
//            $this->validate($request, [
//                'visitor.name' => 'required|min:2|max:50',
//                'visitor.phone' => 'required|integer',
//                'visitor.scene' => 'required|integer',
//                'visitor.note' => 'required||max:500',
//            ],[
//                'required' => ':attribute 为必填项!',
//                'min' => ':attribute 长度不符合要求!',
//                'integer' => ':attribute 必须为整数!',
//            ],[
//                'visitor.name' => '姓名',
//                'visitor.phone' => '年龄',
//                'visitor.scene' => '接入场景',
//                'visitor.note' => '性别',
//            ]);
        if($request->input('short_code') !== $this->short_code_test) return $this->returnMsg( 0 ,'短信验证码有误 !');
        try{
            $data = $request->input('visitor');
            if(VisitorInfo::create($data)){
                return $this->returnMsg(1);
            }
        } catch (QueryException $ex) {
            return $this->returnMsg(0);
        }
    }
    
    // 发送短信验证码
    public function sendCode(){
        $code = $this->short_code_test;
        
        // 发送短信 todo
        
        // 存储session todo
        
        
        return $this->returnMsg(1);
    }

}
