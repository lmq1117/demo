<?php

namespace App\Http\Controllers\Home;

use App\Entity\Home\Goods;
use App\Http\Controllers\WechatController;
use Illuminate\Http\Request;
use App\User;
use App\Entity\Home\Order;

class OrderController extends WechatController
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

        //传* 获取所有状态的
        $type = $req_data['status'] != '*' ? $req_data['status'] : '';

        if($type == ''){
            $orderList =  Order::where('u_id',$user->id)->orderBy('created_at')->get();
        } else {
            $orderList =  Order::where('u_id',$user->id)->where('status',$type)->orderBy('created_at')->get();
        }
        $this->returnMsg['data']['all_order_list'] = $orderList;
        $this->setReturnMsg();
        return $this->returnMsg;
    }

    //从购物车提交商品选中商品，生成订单
    public function makeOrder(Request $request){
        $req_data = $request->all();

        $username = $req_data['username'];
        $user = User::findForId($username);
        $u_id = $user->id;
        $g_id_str = trim($req_data['g_ids'],',');//以逗号分隔的gid字符串，跟
        $g_ids = explode(',',$g_id_str);
        $session = json_decode($this->session->get($this->session_key),true);

        //生成订单号
        $order_no = date('YmdHis',time()).mt_rand(111111,999999);

        //遍历商品 计算总价
        $order_amount = 0;
        $order_goods_total_num = 0;
        $orderInfo = [];
        foreach ($g_ids as $g_id => $val){
            $shopping_cart_goods_info = $session['shopping_cart'][$g_id];
            $goods = Goods::find($val);
            $shopping_cart_goods_info['goods_price'] = $goods->goods_price;
            $order_amount = $order_amount + $shopping_cart_goods_info['cart_num'] * $goods->goods_price;
            $order_goods_total_num++;
            $orderInfo[$g_id] = $shopping_cart_goods_info;

        }
        //将订单信息写入订单表
        $order = new Order;
        $order->order_no = $order_no;
        $order->order_desc = '';
        $order->remark = '';
        $order->u_id = $u_id;
        $order->status = 0;
        $order->amount = $order_amount;
        $order->discount = 0;
        $order->pay_type = 3;//1 支付宝 3 微信
        $order->third_pay_no = '';
        $order->ship_type = '';
        $order->ship_no = '';
        $order->save();

        //将订单详情写入订单详情表
        if($order->id){
            foreach ($orderInfo as $key => $val){
                $order_info = new OrderInfo;
                $order_info->u_id = $u_id;
                $order_info->g_id = $key;
                $order_info->num = $val['cart_num'];
                $order_info->price = $val['goods_price'];
                $order_info->goods_name = $val['goods_name'];
                $order_info->save();
                if($order_info->id){//插入成功
                    //取 改 存
                    $session = json_decode($this->session->get($this->session_key),true);
                    unset($session['shopping_cart'][$key]);//从购物车中删除该商品
                    $this->session->set($this->session_key,json_encode($session));
                }

            }
        }
        $this->setReturnMsg(0);
        $this->returnMsg['data']['o_id'] = $order->id;
        return $this->returnMsg;
    }


    //订单结算页
    public function getPayPage (Request $request){
        $req_data = $request->all();
        $o_id = $req_data['o_id'];
        $order = Order::find($o_id);
        $this->returnMsg['data']['order'] = $order;
        $order_info = OrderInfo::where('o_id',$o_id)->get();
        $this->returnMsg['data']['order_info'] = $order_info;
        $this->setReturnMsg(0);
        return $this->returnMsg;
    }




}
