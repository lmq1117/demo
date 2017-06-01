<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Wechat\wechatCallbackapiTest;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{

    public function index(Request $request){
        $req_data = $request->all();
        $postStr = file_get_contents("php://input");
        Log::info('----Wechat request----'.json_encode($req_data));
        Log::info('----Wechat request2----'.$postStr);

        //将xml字符串转换为对象
        $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);

        $fromUsername = $postObj->FromUserName;    //谁发给我的
        $toUsername = $postObj->ToUserName;         //发给谁的 公众号的微信号
        $keyword = trim($postObj->Content);         //发的啥 文本消息有

        if($postObj->MsgType == 'text'){
            //文本消息
        } elseif ($postObj->MsgType == 'event'){
            //事件
            switch ($postObj->Event){
                case 'CLICK':

                    break;
                case 'VIEW':
                    if($postObj->MenuId == '412801741'){
                        //进入首页了
                        //根据openid去微信服务器拿资料
                        //$userInfo = getUserInfo($postObj->FromUserName);
                        //file_put_contents('/tmp/wco_user_info.log',date('Y-m-d H:i:s',time()).'----'.json_encode($userInfo)."\r\n",FILE_APPEND);
                        //写session

                    }
                    break;
            }

        }

        return "<xml>
        <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
        <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
        <CreateTime>".time()."</CreateTime>
        <MsgType><![CDATA[image]]></MsgType>
        <Image>
        <MediaId><![CDATA[ABHRIwdrVPsHuqtJ0zt8RPoPzoySI1cy8Xh29oe869nEU9MSpvdlCSiwFyObbEGn]]></MediaId>
        </Image>
        </xml>";



    }

    //微信url校验 校验通过后 改路由指向index方法
    public function verifyUrl(Request $request){
        $req_data = $request->all();
        $data['token'] = 'shop_demo';
        $data['echostr'] = $req_data['echostr'];
        $data['signature'] = $req_data['signature'];
        $data['timestamp'] = $req_data['timestamp'];
        $data['nonce'] = $req_data['nonce'];

        $wechatObj = new wechatCallbackapiTest($data);
        return $wechatObj->valid();
    }
}
