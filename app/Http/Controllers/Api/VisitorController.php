<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;
use Validator;

class VisitorController extends ApiController
{
    public function test_nginx(){
        var_demp('wwww');exit;
    }
    
    // 接收前端提交的表单的
    public function getFrom(Request $request){
        $validator = Validator::make($request->all(),[
            'phone'=>'required|exists:users',
            'password'=>'required|between:6,32'
        ]);

        if($request->isMethod('POST')){
            // 1. 控制器验证
            $this->validate($request, [
                'visitor.name' => 'required|min:2|max:50',
                'visitor.phone' => 'required|integer',
                'visitor.scene' => 'required|integer',
                'visitor.note' => 'required||max:500',
            ],[
                'required' => ':attribute 为必填项!',
                'min' => ':attribute 长度不符合要求!',
                'integer' => ':attribute 必须为整数!',
            ],[
                'visitor.name' => '姓名',
                'visitor.phone' => '年龄',
                'visitor.scene' => '接入场景',
                'visitor.note' => '性别',
            ]);
            
            try{
                $data = $request->input('visitor');
                if(VisitorInfo::create($data)){
                    return $this->setReturnMsg(0);
                }
            } catch (QueryException $ex) {
                return $this->setReturnMsg(5);
            }
        }
        return $this->setReturnMsg(5);
    }

}
