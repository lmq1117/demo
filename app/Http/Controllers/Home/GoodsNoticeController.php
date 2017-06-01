<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Entity\Home\GoodsNotice;
use App\Http\Controllers\Home\ApiTmpController;
use App\User;

class GoodsNoticeController extends Controller
{
    //用户添加商品关注
    public function goodsNoticeAdd(Request $request){
        $data = $request->all();
        $goods_notice = new GoodsNotice();
        $goods_notice->g_id = $data['g_id'];

        $user_name = $data['username'];
        $user = User::where('name',$user_name)->first();
        $goods_notice->u_id = $user->id;
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

}