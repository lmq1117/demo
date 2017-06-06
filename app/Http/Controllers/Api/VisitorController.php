<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Home\SakyaController;
use Illuminate\Http\Request;
use App\Entity\Home\VisitorInfo;
use \Illuminate\Database\QueryException;

class VisitorController extends SakyaController{   
    
    use \App\Http\Controllers\Traits\SmsTrait;
    
    public function test(){
        var_dump('ok with message !');exit;
        return phpinfo();
    }
    // 接收前端提交的表单的
    public function getFrom(Request $request){
        //visitor[name]=xuteng&visitor[phone]=15549494949&visitor[scene]=7&visitor[note]=terqrqwrqwrqwrqwrwqrwrqqwrqwrqwrqwrqwrqw&short_code=123456
        // 1. 控制器验证
        $this->validate($request, [
            'visitor.name' => 'required|min:2|max:50',
            'visitor.phone' => 'required|min:2|max:20',
            'visitor.scene' => 'required|integer',
            'visitor.note' => 'max:500',
        ],[
            'required' => ':attribute为必填项!',
            'min' => ':attribute长度不符合要求!',
            'max' => ':attribute长度过长!',
            'integer' => ':attribute必须为整数!',
        ],[
            'visitor.name' => '姓名',
            'visitor.phone' => '联系电话',
            'visitor.scene' => '接入场景',
            'visitor.note' => '需求备注',
        ]);
        $data = $request->input('visitor');
        $short_code = $request->input('short_code');
        if(!$this->verifyPhoneCode( $short_code , $data['phone'])) return $this->returnMsg( 0 ,'短信验证码有误!');
        try{
            if(VisitorInfo::create($data)){
                return $this->returnMsg(1);
            }
        } catch (QueryException $ex) {
            return $this->returnMsg(0);
        }
    }
    
    // 发送短信验证码
    public function sendCode(Request $request){
        $this->validate($request, [
            'visitor.phone' => 'required|min:2|max:20'
        ],[
            'required' => ':attribute为必填项!',
            'min' => ':attribute长度不符合要求!',
            'max' => ':attribute长度过长!'
        ],[
            'visitor.phone' => '联系电话'
        ]);
        $data = $request->input('visitor'); 
        $phone = $data['phone'];
        // 发送短信 todo
        $this->sendSms($phone);
        return $this->returnMsg(1);
    }

}
