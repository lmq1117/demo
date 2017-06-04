<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\WechatController;

class AddressController extends WechatController
{
    /*
     * 提交订单逻辑后的
     * 1 检查是否绑定手机 是 往下走 不是 绑定手机去
     * 2 检查是否设置并选中地址 是 往下走 生成订单，不是 添加地址并选中
     * 3 根据购物车信息生成订单
     * 4 点微信支付，掉jsapi方式去微信app支付
     */


    //检查
    public function checkAddress(Request $request){
        $req_data = $request->all();

    }

    public function checkVerifyPhone(Request $request){

    }



}
