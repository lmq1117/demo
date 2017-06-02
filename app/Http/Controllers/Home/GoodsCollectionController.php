<?php

namespace App\Http\Controllers\Home;
use App\Entity\Home\GoodsCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Home\ApiTmpController;
use App\User;

class GoodsCollectionController extends ApiTmpController
{
    //用户添加商品收藏
    public function goodsCollectionAdd(Request $request){

        $data = $request->all();
        $goods_collection = new GoodsCollection();
        $goods_collection->g_id = $data['g_id'];

        //$user_info = Auth::user();
        //$goods_collection->u_id = $user_info->id;

        //$user = new User();
        $user_name = $data['username'];
        //$user->name = $user_name;
        //$user->find;
        $user = User::where('wx_openid',$user_name)->first();
        //return $user;


        //$goods_collection->g_id = $data['g_id'];
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
}
