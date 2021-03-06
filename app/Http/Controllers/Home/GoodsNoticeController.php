<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\WechatController;
use Illuminate\Http\Request;
use App\Entity\Home\GoodsNotice;
//use App\Http\Controllers\Home\ApiTmpController;
use App\User;

class GoodsNoticeController extends WechatController
{
    //用户添加商品关注
    public function goodsNoticeAdd(Request $request){
        $data = $request->all();
        $goods_notice = new GoodsNotice();
        $goods_notice->g_id = $data['g_id'];

        $user_name = $data['username'];
        $user = User::where('wx_openid',$user_name)->first();
        $goods_notice->u_id = $user->id;

        if($check_duplicate = GoodsNotice::where('u_id',$goods_notice->u_id)->where('g_id',$goods_notice->g_id)->first()){
            //关注失败
            $this->setReturnMsg(3);
            return $this->returnMsg;
        }
        $goods_notice->save();

        if($goods_notice->id){
            //关注成功
            $this->setReturnMsg(0);

        } else {
            //关注失败
            $this->setReturnMsg(2);
        }
        return $this->returnMsg;

    }


    //取消关注商品
    public function goodsNoticeCancel(Request $request){
        $req_data = $request->all();
        $g_id = $req_data['g_id'];
        $appid = $req_data['username'];
        $user = User::findForId($appid);
        $goods_notice = GoodsNotice::where('u_id',$user->id)->where('g_id',$g_id)->first();
        $goods_notice->is_cancel = 1;
        $goods_notice->save();
        $this->setReturnMsg(0);
        return $this->returnMsg;
    }

}
