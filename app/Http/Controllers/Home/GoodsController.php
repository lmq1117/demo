<?php

namespace App\Http\Controllers\Home;
use App\Entity\Home\GoodsEstimate;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
//use App\Http\Controllers\ApiController;
//#use App\Entity\Home\{Goods,GoodsNotice,GoodsCollection};
use App\Entity\Home\Goods;
use App\Entity\Home\GoodsNotice;
use App\Entity\Home\GoodsCollection;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class GoodsController extends CommonController
{
    //
    public function test(Request $request){
        //return 'goods_test----'.date('Y-m-d H:i:s',time());
        //$user = DB::table('users')->where('wx_openid','outktv28lv2UjvPTeT1TvKRRx0tc')->first();
        //$user->toArray();
        //var_dump($openid);
        //$arr = Cache::get('userInfo_outktv28lv2UjvPTeT1TvKRRx0tc');
        //$arr = [
        //    'name'=>'mary',
        //    'age'=>18
        //];

        //$arr_cache = Cache::put('userInfo_outktv28lv2UjvPTeT1TvKRRx0tc',$arr,1);
        //$arr_cache = Cache::get('userInfo_outktv28lv2UjvPTeT1TvKRRx0tc');
        //var_dump($arr_cache);

        //$res = Cache::put('key',$arr,10);
        //var_dump($res);

        //cache(['key001'=>$arr],1);
        //$res = cache('accessToken');
        //$res = cache(['accessToken'=>1],1);
        //var_dump(date('Y-m-d H:i:s',time()));
        //var_dump($res);

        //var_dump($user);

        //$user = User::find(1);
        //return $user->toArray();
        //$user = $user->toArray();
        //echo '<pre>';
        //print_r($user);
        //echo '</pre>';

        //return $user;
        //return session('userInfo','aaaaa');
        //$request->session()->put('userInfo','lmq');
        //Redis::set('name','mary jack');
        //session(['userInfo'=>'lmq1117']);
        //$data = $request->session()->all();
        //return $data;


        //测试阿里大于短信发送方法
        //$smsResult = $this->sendSms('18129931017');
        //Log::info('短信发送结果goods----'.json_encode($smsResult));
        //return $smsResult;
        $session = json_decode($this->session->get('sessionid_opSdF1XAenS-5lhSBq7GIC45pKSc'));
        dd($session);
    }

    public function sessionTest(Request $request){
        //return $request->session()->all();
        //$user = Auth::user();
        //return $user->id;
        $data = $request->session()->all();
        return $data;
    }


    public function getOne(){
        $good = Goods::find(1);
        $this->returnMsg['data'] = $good;
        $this->setReturnMsg(0);

        return $this->returnMsg;
    }

    public function getShopHomeGoods(){

        //首页三年个推荐商品
        $promotionGoods = Goods::select('goods_id as id','goods_name as name','goods_price as price')
            ->where('is_promotion',1)
            ->where('is_on_sale','1')
            ->take(3)
            ->orderBy('goods_id', 'desc')
            ->get();
        foreach($promotionGoods as &$val){
            $val->toArray();
        }
        $this->returnMsg['data']['promotion'] = $promotionGoods->toArray();

        //要排除的商品id
        $exclude_goods_ids = [];
        foreach ($promotionGoods as $key=>$value){
            $exclude_goods_ids[] = $value->id;
        }

        //所有在架商品
        $allOnSaleGoods = Goods::select('goods_id as id','goods_name as name','goods_price as price')
            ->where('is_on_sale','1')
            ->take(30)
            ->orderBy('goods_id', 'desc')
            ->get();
        foreach ($allOnSaleGoods as &$value){
            $value->toArray();
            if(in_array($value->id,$exclude_goods_ids)){
                unset($value);
            }
        }

        $this->returnMsg['data']['normal_goods'] = $allOnSaleGoods->toArray();


        $this->setReturnMsg(0);
        return $this->returnMsg;
    }

    //获取商品详情
    //商品id要在post里
    public function getGoodsDetail(Request $request){
        $getData = $request->all();
        $gid = $getData['g_id'];
        $goods = Goods::find($gid);//商品基础信息

        //商品收藏数量
        $goods_collection = new GoodsCollection();
        $goods['collection_num'] = $goods_collection->countUser($gid);

        //商品关注数量
        $goods_notice = new GoodsNotice();
        $goods['notice_num'] = $goods_notice->countUser($gid);

        $this->returnMsg['data']['goods_info'] = $goods;
        $this->setReturnMsg(0);
        return $this->returnMsg;

    }

    //获取当前用户关注的所有商品信息（用于商品列表）
    public function goodsNoticeList(Request $request){
        $req_data = $request->all();

        $user = User::findForId($req_data['username']);
        $notice = GoodsNotice::where('u_id',$user->id)->where('is_cancel',0)->get();
        foreach ($notice as &$val){
            $goods_info = Goods::find($val->g_id);
            $val->goodsInfo = $goods_info;
        }
        //return $notice;
        $this->returnMsg['data']['notice_list'] = $notice;
        $this->setReturnMsg(0);
        return $this->returnMsg;

    }


    //获取当前用户收藏的所有商品信息（用于商品列表）
    public function goodsCollectionList(Request $request){
        $req_data = $request->all();
        $user = User::findForId($req_data['username']);
        $collection = GoodsCollection::where('u_id',$user->id)->where('is_cancel',0)->get();
        foreach($collection as &$val){
            $goods_info = Goods::find($val->g_id);
            $val->goodsInfo = $goods_info;
        }

        $this->returnMsg['data']['collection_list'] = $collection;
        $this->setReturnMsg(0);
        return $this->returnMsg;
    }

    //测试ajax跨域2方法
    public function index(){
        return ['name'=>'mary','age'=>18,'sex'=>0];
    }

    public function getstr2(){
        return ['name'=>'lili','age'=>17,'sex'=>0];
    }

    //商品评价
    public function goodsEstimate(Request $request){
        $req_data = $request->all();
        $o_id = $req_data['o_id'];
        $g_id = $req_data['g_id'];
        //该订单该商品是否已经评价过,评价过就不给评论
        $res = GoodsEstimate::where('o_id',$o_id)->where('g_id',$g_id)->first();
        if($res){
            $this->setReturnMsg(5);
            return $this->returnMsg;
        }




    }
    //获取商品评价
    public function getGoodsEstimate(Request $request){

    }
}
