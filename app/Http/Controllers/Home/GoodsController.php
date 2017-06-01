<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Home\ApiTmpController;
#use App\Entity\Home\{Goods,GoodsNotice,GoodsCollection};
use App\Entity\Home\Goods;
use App\Entity\Home\GoodsNotice;
use App\Entity\Home\GoodsCollection;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GoodsController extends ApiTmpController
{
    //
    public function test(){
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
        return session('userInfo');

    }

    public function sessionTest(Request $request){
        //return $request->session()->all();
        $user = Auth::user();
        return $user->id;
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

        $this->returnMsg['data']['collection_list'] = $collection;
        $this->setReturnMsg(0);
        return $this->returnMsg;
    }
}
