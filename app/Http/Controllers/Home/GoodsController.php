<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Home\ApiTmpController;
#use App\Entity\Home\{Goods,GoodsNotice,GoodsCollection};
use App\Entity\Home\Goods;
use App\Entity\Home\GoodsNotice;
use App\Entity\Home\GoodsCollection;
use Illuminate\Support\Facades\Auth;

class GoodsController extends ApiTmpController
{
    //
    public function test(){
        return 'goods_test----'.date('Y-m-d H:i:s',time());
    }

    public function sessionTest(Request $request){
        //return $request->session()->all();
        $user = Auth::user();
        return $user;
    }


    public function getOne(){
        $good = Goods::find(1);
        $this->returnMsg['data'] = $good;
        $this->setReturnMsg();

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


        $this->setReturnMsg('获取首页商品成功');
        return $this->returnMsg;
    }

    //获取商品详情
    //商品id要在post里
    public function getGoodsDetail(Request $request){
        $getData = $request->all();
        $gid = $getData['gid'];
        $goods = Goods::find($gid);//商品基础信息

        //商品收藏数量
        $goods_collection = new GoodsCollection();
        $goods['collection_num'] = $goods_collection->countUser($gid);

        //商品关注数量
        $goods_notice = new GoodsNotice();
        $goods['notice_num'] = $goods_notice->countUser($gid);

        $this->returnMsg['data'] = $goods;
        $this->setReturnMsg('获取商品详情成功！');
        return $this->returnMsg;

    }
}
