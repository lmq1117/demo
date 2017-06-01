<?php
/**
 * Created by PhpStorm.
 * User: kevinatSunEee
 * Date: 2017/6/1
 * Time: 15:40
 */
namespace App\Tools\Wechat;
use Illuminate\Support\Facades\Cache;
class Wechat{
    private $appid;
    private $appSecret;
    private $token;//url验证token
    private $accessToken;
    private $key;//加密字符串
    public function __construct($wechat_config)
    {
        $this->config($wechat_config);
    }

    private function config($config){
        foreach ($config as $key=>$val){
            $this->$key=$val;
        }
    }

    public function getWxUserInfo($openid){
        $accessTokenObj = $this->getAccessToken();
        $accessToken = $accessTokenObj['access_token'];
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accessToken."&openid=".$openid."&lang=zh_CN";
        return $this->curlGet($url);
    }


    public function getAccessToken(){
        //$appid = $this->appid;
        //$appSecret = $this->appSecret;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appSecret;

        //if(file_exists('./token.cache') && time() - filemtime('./token.cache') < 7100){
        //    return file_get_contents('./token.cache');
        //} else {
        //    $res = curl_get($url);
        //    file_put_contents('./token.cache',$res['access_token']);
        //    return $res['access_token'];
        //}

        if($accessToken = cache('accessToken')){
            return $accessToken;
        } else {
            $accessToken = $this->curlGet($url);
            //Cache::put('accessToken',$accessToken,110);//缓存时间 110分钟
            cache(['accessToken'=>$accessToken],110);//缓存时间 110分钟
            return $accessToken;
        }


    }

    protected function curlPost($url,$data){
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res,true);
    }

    protected function curlGet($url){
        //curl get请求
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//以结果的方式返回请求到的结果
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res,true);
    }
}