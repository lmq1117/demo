<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\WechatController;
use Illuminate\Http\Request;
use App\Http\Controllers\Home\ApiTmpController;
use App\User;
use App\Entity\Home\Order;

class UserController extends WechatController
{
    //会员中心所需数据
    public function getUserInfo(Request $request){
        $req_data = $request->all();
        $username = $req_data['username'];
        $user = User::findForId($username);

        //订单统计
        //待付款
        $order_status_count['before_pay'] = Order::getOrderStatusCount($user->id,0);
        //待发货
        $order_status_count['before_shipping'] = Order::getOrderStatusCount($user->id,1);
        //已发货
        $order_status_count['had_deliver'] = Order::getOrderStatusCount($user->id,2);
        //已完成
        $order_status_count['had_complete'] = Order::getOrderStatusCount($user->id,3);
        $this->returnMsg['data']['order_status_count'] = $order_status_count;
        //全部订单
        //另写了接口

        //我的购物车
        //ing

        //我的关注
        //另写了接口

        //我的收藏
        //另写了接口

        //我的退货
        //ing
        $this->setReturnMsg(0);
        return $this->returnMsg;





    }

    //会员绑定手机号码表单提交到这里
    public function bindPhone(Request $request){
        $req_data = $request->all();
        $u_id = $req_data['u_id'];
        $phone_num = $req_data['phone'];
        $code = $req_data['code'];

        //验证短信验证码
        if($this->verifyPhoneCode($phone_num,$code)){
            //短信验证码
            $user = User::find($u_id);
            $user->phone = $phone_num;
            $user->save();

            $this->setReturnMsg(0);
            return $this->returnMsg;
        }
    }
}
