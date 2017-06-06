<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Home\SakyaController;
use Illuminate\Http\Request;
use App\Entity\Home\CompanyInfo;
use \Illuminate\Database\QueryException;

class CompanyController extends SakyaController{   
    
    use \App\Http\Controllers\Traits\SmsTrait;
    
    public function test(){
        var_dump('ok with message !');exit;
        return phpinfo();
    }
    // 接收前端提交的表单的
    public function getFrom(Request $request){
        //company[company]=深深科技有限公司&company[value]=17000&company[name]=xuteng&company[phone]=15549494949&company[scene]=7&company[note]=terqrqwrqwrqwrqwrwqrwrqqwrqwrqwrqwrqwrqw&short_code=123456
        // company[name]=rww&company[phone]=15549494949&company[scene]=7&company[note]=12412421&short_code=123456&company[value]=17000&company[company]=深深科技有限公司
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
        $data = $request->input('company');
        $short_code = $request->input('short_code');
        if(!$this->verifyPhoneCode( $short_code , $data['phone'])) return $this->returnMsg( 0 ,'短信验证码有误!');
        try{
            if(CompanyInfo::create($data)){
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
