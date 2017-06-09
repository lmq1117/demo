<?php

namespace App\Http\Controllers\Home;

use App\Entity\Address;
use App\Entity\Areas;
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

    //显示三级地址列表
    public function getAreaInfo(Request $request){
        $req_data = $request->all();
        $path = isset($req_data['path']) ? $req_data['path'] : '';

        switch($path){
            case ''://查出省信息
                $area[1] = Areas::where('parentid','1')->get();
                break;
            case substr_count($path,',') == 1://查省下面的所有市
                $path = rtrim($path,',');
                $pathArr = explode(',',$path);
                $sheng_id = $pathArr[0];
                $area[1] = Areas::where('parentid','1')->get();
                $area[2] = Areas::where('parentid',$sheng_id)->get();
                break;
            case substr_count($path,',') == 2://查市下面的所有县市or区
                $path = rtrim($path,',');
                $pathArr = explode(',',$path);
                $sheng_id = $pathArr[0];
                $shi_id = $pathArr[1];
                $area[1] = Areas::where('parentid','1')->get();
                $area[2] = Areas::where('parentid',$sheng_id)->get();
                $area[3] = Areas::where('parentid',$shi_id)->get();
                break;
        }
        $this->returnMsg['data']['area_info'] = $area;
        $this->setReturnMsg(0);
        return $this->returnMsg;

    }

    //添加地址
    public function addaddress(Request $request){
        $req_data = $request->all();
        $u_id = $req_data['u_id'];
        $path = $req_data['path'];//看前端怎么传过来
        $detail = $req_data['detail_address'];
        $count = Address::where('u_id',$u_id)->count();
        $count = $count == 0 ? 1 : $count;
        if($count == 1){ //第一条地址直接设置为默认地址
            $is_default = 1;
        } else {
            $is_default = 0;
        }






    }




}
