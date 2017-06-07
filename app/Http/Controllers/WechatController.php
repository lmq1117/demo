<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Wechat\wechatCallbackapiTest;
use Illuminate\Support\Facades\Log;
use App\User;
use App\Tools\Wechat\Wechat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
class WechatController extends CommonController
{


    public function __construct()
    {
        parent::__construct();
    }

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

        $session['post_obj'] = $postObj;
        $userInfo = User::findForId($fromUsername);
        if($userInfo){
            $session['userInfo'] = $userInfo;
        }

        $this->session_key = 'sessionid_'.$fromUsername;
        $this->session->set($this->session_key,json_encode($session));

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
                        $session = json_decode($this->session->get($this->session_key),true);
                        $session['userInfo']=$userInfo;
                        $this->session->set($this->session_key,json_encode($session));
                        log::info('----getWxUserInfo----'.json_encode($userInfo));
                        //Cache::put('userInfo_'.$postObj->FromUserName,$userInfo,5);
                        //$cache_key = 'userInfo_'.$postObj->FromUserName;
                        //$cache_data[$cache_key] = $userInfo;
                        //cache($cache_data,2);//缓存2分钟

                        //$u = cache($cache_key);
                        //log::info('----cache write----'.json_encode($u));

                        //{
                        //    "subscribe": 1,
                        //    "openid": "outktv28lv2UjvPTeT1TvKRRx0tc",
                        //    "nickname": "李明权",
                        //    "sex": 1,
                        //    "language": "zh_CN",
                        //    "city": "深圳",
                        //    "province": "广东",
                        //    "country": "中国",
                        //    "headimgurl": "http://wx.qlogo.cn/mmopen/PiajxSqBRaEJQdJZQiaBZl20ghgE9XLrPR7PTuj154CpsgcTjpg9ETPnFoY5Q98bFLnuk000PuVc0IJqPAbWsdZw/0",
                        //    "subscribe_time": 1496312239,
                        //    "remark": "",
                        //    "groupid": 0,
                        //    "tagid_list": []
                        //}
                        $user = new User;
                        $user->name = $userInfo['nickname'];
                        $user->wx_openid = $userInfo['openid'];
                        $user->save();

                    }


                    break;
                //取消关注事件
                case 'unsubscribe':
                    echo 'aaaa';
                    break;
                case 'CLICK':
                    echo 'aaaa';
                    break;
                case 'VIEW':
                    if($postObj->EventKey == 'http://shinehua.duapp.com/'){
                        //进入首页了 写session
                        //根据openid去微信服务器拿资料
                        //$userInfo = getUserInfo($postObj->FromUserName);
                        //file_put_contents('/tmp/wco_user_info.log',date('Y-m-d H:i:s',time()).'----'.json_encode($userInfo)."\r\n",FILE_APPEND);
                        //写session
                        Log::info('----Wechat request3----'.$postStr);
                        $user = User::where('wx_openid',$postObj->FromUserName)->first();
                        //$user = $user->toArray();
                        Log::info('----$user----'.json_encode($user));
                        //session(['userInfo'=>$postObj->FromUserName]);
                        //$request->session()->put('userInfo',$postObj->FromUserName);



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

    public function createMemu(){
        $wobj = new Wechat($this->config);
        $menu = '{
                     "button":[
                      {
                           "name":"走进烨华",
                           "sub_button":[
                           {    
                               "type":"view",
                               "name":"烨华介绍",
                               "url":"http://wqiye.lmqde.com/index.html"
                            },
                            {
                               "type":"view",
                               "name":"官网",
                               "url":"http://wqiye.lmqde.com/index.html"
                            }]
                       },
                       
                       {
                           "name":"给我派单",
                           "sub_button":[
                           {    
                               "type":"view",
                               "name":"支付接入",
                               "url":"http://wqiye.lmqde.com/payJoint.html"
                            },
                            {
                               "type":"view",
                               "name":"微应用",
                               "url":"http://wqiye.lmqde.com/proposer.html"
                            },
                            {
                               "type":"view",
                               "name":"网站门户",
                               "url":"https://www.shinehua.cn"
                            }]
                       },
                       {
                           "name":"新品上架",
                           "sub_button":[
                           {    
                               "type":"view",
                               "name":"微商城",
                               "url":"http://wshop.lmqde.com"
                            },
                            {
                               "type":"view",
                               "name":"棋牌休闲",
                               "url":"http://wshop.lmqde.com"
                            }]
                       }
                       
                       ]
                 }';

        $res = $wobj->createWxMenu($menu);
        Log::Info(date('Y-m-d H:i:s',time()).'---生成菜单---'.json_encode($res));
        return $res;
    }
}
