<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Home\ApiTmpController;
use App\User;
use App\Entity\Home\Order;

class OrderController extends ApiTmpController
{
    //
    //public function

    //获取用户下所有订单信息
    public function getAllOrderList(Request $request){

        $req_data = $request->all();
        $username = $req_data['username'];
        $user = User::findForId($username);

        $orderList =  Order::where('u_id',$user->id)->orderBy('created_at')->get();
        $this->returnMsg['data']['all_order_list'] = $orderList;
        $this->setReturnMsg();
        return $this->returnMsg;
    }

    //获取用户下所有订单信息
    public function getStatusOrderList(Request $request){

        $req_data = $request->all();
        $username = $req_data['username'];
        $user = User::findForId($username);

        $type = $req_data['status'];

        $orderList =  Order::where('u_id',$user->id)->where('status',$type)->orderBy('created_at')->get();
        $this->returnMsg['data']['all_order_list'] = $orderList;
        $this->setReturnMsg();
        return $this->returnMsg;
    }
}
