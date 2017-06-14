<?php

namespace App\Http\Controllers\Home;
use App\Entity\Home\GoodsCollection;
use App\Http\Controllers\WechatController;
use Illuminate\Http\Request;
//use App\Http\Controllers\Home\ApiTmpController;
use App\User;

class GoodsCollectionController extends WechatController
{
    //用户添加商品收藏
    public function goodsCollectionAdd(Request $request){

        $data = $request->all();
        $goods_collection = new GoodsCollection();
        $goods_collection->g_id = $data['g_id'];

        $user_name = $data['username'];

        $user = User::where('wx_openid',$user_name)->first();

        $goods_collection->u_id = $user->id;
        if($check_duplicate = GoodsCollection::where('u_id',$goods_collection->u_id)->where('g_id',$goods_collection->g_id)->first()){
            //关注失败
            $this->setReturnMsg(4);
            return $this->returnMsg;
        }

        $goods_collection->save();
        //return $goods_collection->id;
        //$goods_collection->u_id = $data['u_id'];
        //if()
        if($goods_collection->id){
            //收藏成功
            $this->setReturnMsg(0);
        } else {
            //收藏失败
            $this->setReturnMsg(1);
        }
        return $this->returnMsg;

    }


    //取消收藏商品
    public function goodsCollectionCancel(Request $request){
        $req_data = $request->all();
        $g_id = $req_data['g_id'];
        $appid = $req_data['username'];
        $user = User::findForId($appid);
        $goods_collection = GoodsCollection::where('u_id',$user->id)->where('g_id',$g_id)->first();
        $goods_collection->is_cancel = 1;
        $goods_collection->save();
        $this->setReturnMsg(0);
        return $this->returnMsg;
    }
}
