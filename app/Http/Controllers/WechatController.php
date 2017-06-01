<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Wechat\wechatCallbackapiTest;
use Illuminate\Support\Facades\Log;
use App\User;
use App\Tools\Wechat\Wechat;
use Illuminate\Support\Facades\Cache;

class WechatController extends CommonController
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
                //关注事件
                //关注后做一件事，检查user表中openid 有木有这个openid 没有的话插入一条，xml也插入一条，有的话啥也不干
                //将openid写入user表
                case 'subscribe':
                    //关注时获取wx_openid 并新增user
                    //查询User表中有无该open_id
                    $wx_openids = DB::table('users')->where('wx_openid',$postObj->FromUserName)->first();
                    if(!$wx_openids){
                        //获取用户信息
                        $wechatTools = new Wechat($this->config);
                        $userInfo = $wechatTools->getWxUserInfo($postObj->FromUserName);
                        Cache::put('userInfo_'.$postObj->FromUserName,$userInfo,5);

                    }


                    break;
                //取消关注事件
                case 'unsubscribe':
                    break;
                case 'CLICK':

                    break;
                case 'VIEW':
                    if($postObj->EventKey == 'http://shinehua.duapp.com/'){
                        //进入首页了 写session
                        //根据openid去微信服务器拿资料
                        //$userInfo = getUserInfo($postObj->FromUserName);
                        //file_put_contents('/tmp/wco_user_info.log',date('Y-m-d H:i:s',time()).'----'.json_encode($userInfo)."\r\n",FILE_APPEND);
                        //写session
                        Log::info('----Wechat request3----'.$postStr);


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
