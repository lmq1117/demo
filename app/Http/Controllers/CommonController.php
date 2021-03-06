<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Tools\SMS\Alidayu\TopClient;
use App\Tools\SMS\Alidayu\AlibabaAliqinFcSmsNumSendRequest;
use Illuminate\Support\Facades\Log;

class CommonController extends ApiController
{
    //操作session的redis连接对象
    protected $session;

    protected $session_key;

    protected $config = [
        //测试服务号
        //'appid'=>'wx858b762dc6af6249',
        //'appSecret'=>'1ab68b48c760948ee041173508c3fcfc'

        //服务号
        'appid'=>'wx792ffa17436005f9',
        'appSecret'=>'230b2f56435cc8191d74e86350f7d258'
    ];

    public function __construct()
    {
        //parent::__construction();
        //使用redis db1
        $this->session = Redis::connection('session');

    }

    protected function sendSms($phone_num){
        $userInfo = json_decode($this->session->get($this->session_key));
        $name = $userInfo['username'];
        $c = new TopClient;
        $c->appkey = '23888067';
        $c->secretKey = 'b760ea344aadc165f0dc7b9cae52cd5d';
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $rand_num = mt_rand(11111,99999);
        $req->setExtend($rand_num);
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("烨华科技");
        //$req->setSmsParam("{\"code\":\"1234\",\"product\":\"alidayu\",\"name\":\"李明权\",\"smscode\":\"{$rand_num}\"}");
        $req->setSmsParam("{\"name\":\"{$name}\",\"smscode\":\"{$rand_num}\"}");
        $req->setRecNum($phone_num);

        //绑定用户手机号码用到的短信模板
        $req->setSmsTemplateCode("SMS_69980172");
        $resp = $c->execute($req);
        Log::info('短信发送结果----'.json_encode($resp));
        //Log::info('短信发送结果对象查看err_code----'.$resp->result->err_code);
        //Log::info('短信发送结果对象查看success----'.$resp->result->success);

        //Log::info();
        if($resp->result->err_code == 0){
            //发送短信成功，将短信验证码存入redis  key规则：手机号码."_phone_code",有效期5分钟
            //18129921017_phone_code
            $phone_code_key = $phone_num . '_phone_code';
            $this->session->setex($phone_code_key,300,$rand_num);
        }
        return $resp;
    }

    protected function verifyPhoneCode($phone_code,$phone_num){
        $code_in_session = $this->session->get($phone_num . '_phone_code');
        if($code_in_session == $phone_code){
            return true;
        } else {
            return false;
        }
    }

}
