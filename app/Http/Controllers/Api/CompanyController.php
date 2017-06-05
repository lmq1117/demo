<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Home\SakyaController;
use Illuminate\Http\Request;
use App\Entity\Home\CompanyInfo;
use \Illuminate\Database\QueryException;

class CompanyController extends SakyaController
{   
    private $short_code_test = '123456';
    
    public function test(){
        var_dump('ok !');exit;
        return phpinfo();
    }
    // 接收前端提交的表单的
    public function getFrom(Request $request){
        //company[company]=深深科技有限公司&company[value]=17000&company[name]=xuteng&company[phone]=15549494949&company[scene]=7&company[note]=terqrqwrqwrqwrqwrwqrwrqqwrqwrqwrqwrqwrqw&short_code=123456
        // 1. 控制器验证
//        var_dump($request->all());exit;
        $this->validate($request, [
            'company.name' => 'required|min:2|max:50',
            'company.phone' => 'required|min:2|max:20',
            'company.value' => 'required|integer',
            'company.scene' => 'required|integer',
            'company.company' => 'required|min:2|max:50',
            'company.note' => 'max:500',
        ],[
            'required' => ':attribute为必填项!',
            'min' => ':attribute长度过短!',
            'max' => ':attribute长度过长!',
            'integer' => ':attribute必须为整数!',
        ],[
            'company.name' => '联系人',
            'company.company' => '公司名称',
            'company.value' => '预估金额',
            'company.phone' => '联系电话',
            'company.scene' => '接入场景',
            'company.note' => '需求备注',
        ]);
        if($request->input('short_code') !== $this->short_code_test) return $this->returnMsg( 0 ,'短信验证码有误!');
        try{
            $data = $request->input('company');
            if(CompanyInfo::create($data)){
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
