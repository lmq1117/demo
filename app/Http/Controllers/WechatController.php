<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Wechat\wechatCallbackapiTest;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{

    public function index(Request $request){
        //$postStr = Request::getContent();
        //$postStr = $request->all();
        $postStr = file_get_contents("php://input");
        Log::info('----Wechat request----'.json_encode($postStr));

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
