<?php

namespace App\Entity\Home;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    /*
     * 统计当前用户各状态下订单数量
     * 0-3是正常订单流程 4-6进入退款流程
     * 订单状态$type 0待付款，1已付款待发货，2已付款已发货，3已付款已收货，4客户申请退款，5客户申请退款并被审核，6退款完成
     */
    public static function getOrderStatusCount($user_id,$type){
        return self::where('status',$type)->where('u_id',$user_id)->count();
    }

    //根据订单号查询出订单所有信息
    public static function queryOrder($order_no){
        $order = self::where('order_no',$order_no)->first();
        $order->orderInfo = OrderInfo::where('o_id',$order->id)->get();
        $address = Address::find($order->address_id);
        $address->pathstr = Areas::getAddressStr($address->path);
        //var_dump($order_info);exit;
        $order->address = $address;
        return $order;
    }
}
