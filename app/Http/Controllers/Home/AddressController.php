<?php

namespace App\Http\Controllers\Home;

use App\Entity\Home\Address;
use App\Entity\Home\Areas;
use Illuminate\Http\Request;
use App\Http\Controllers\WechatController;
use App\User;

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
        //var_dump($req_data);
        //var_dump($path);exit;
        switch($path){
            case ''://查出省信息
                $area['province'] = Areas::where('parent_id','1')->get();
                break;
            case substr_count($path,',') == 1://查省下面的所有市
                $path = rtrim($path,',');
                $pathArr = explode(',',$path);
                $sheng_id = $pathArr[0];
                $area['province'] = Areas::where('parent_id','1')->get();
                $area['city'] = Areas::where('parent_id',$sheng_id)->get();
                break;
            case substr_count($path,',') == 2://查市下面的所有县市or区
                $path = rtrim($path,',');
                $pathArr = explode(',',$path);
                $sheng_id = $pathArr[0];
                $shi_id = $pathArr[1];
                $area['province'] = Areas::where('parent_id','1')->get();
                $area['city'] = Areas::where('parent_id',$sheng_id)->get();
                $area['district'] = Areas::where('parent_id',$shi_id)->get();
                break;
        }
        $this->returnMsg['data']['area_info'] = $area;
        $this->setReturnMsg(0);
        return $this->returnMsg;

    }

    //添加地址
    public function addAddress(Request $request){
        $req_data = $request->all();
        $appid = $req_data['username'];
        $user = User::findForId($appid);
        $u_id = $user->id;
        $path = $req_data['path'];//看前端怎么传过来
        $detail = $req_data['detail_address'];
        $count = Address::where('u_id',$u_id)->count();
        $count = $count == 0 ? 1 : $count;
        if($count == 1){ //第一条地址直接设置为默认地址
            $is_default = 1;
        } else {
            $is_default = 0;
        }

        $address = new Address;
        $address->u_id = $u_id;
        $address->path = $path;
        $address->detail_address = $detail;
        $address->num = $count == 1 ? $count : $count + 1;
        $address->is_default = $is_default;
        $address->to_user = $req_data['to_user'];
        $address->phone = $req_data['phone'];
        $address->post_num = $req_data['post_num'];
        $address->save();
        if($address->id > 0){
            $this->setReturnMsg(0);
            return $this->returnMsg;
        }
    }


    //当前用户下的地址列表
    public function addressList(Request $request){
        $req_data = $request->all();
        $appid = $req_data['username'];
        $user = User::findForId($appid);
        $u_id = $user->id;

        //查询区域表
        //$areas_obj = Areas::all();
        ////var_dump($areas_obj);
        //$areas = [];
        //foreach ($areas_obj as $val){
        //    var_dump($val);
        //    $areas[$val->id]=$val->area_name;
        //}
        //var_dump($areas);


        //查询当前用户的默认地址
        $default_address = Address::where('u_id',$u_id)->where('is_default',1)->first();
        //$path = trim($default_address['path'],',');
        //$pathArr = explode(',',$path);
        //$path_val = '';
        //foreach($pathArr as $value){
        //    $path_val .= $areas[$value];
        //}
        //$default_address['path'] = $path_val;

        if(!is_null($default_address)){
            $default_address->pathstr = Areas::getAddressStr($default_address->path);
        }
        //$res = Areas::getAddressStr($default_address->path);
        //var_dump($default_address);exit;


        //查询当前用户的其它地址
        $other_address_list = Address::where('u_id',$u_id)->where('is_default',0)->get();

        if(!is_null($other_address_list)){
            foreach($other_address_list as &$value){
                //foreach ($value as $val){
                //    $path = trim($value['path'],',');
                //    $pathArr = explode(',',$path);
                //    $path_val = '';
                //    foreach ($pathArr as $v){
                //        $path_val .= $areas[$v];
                //    }
                //    $value['path'] = $path_val;
                //}
                $value->pathstr = Areas::getAddressStr($value->path);
            }
        }

        if(!is_null($other_address_list) || !is_null($default_address)){
            $this->returnMsg['data']['default_address'] = $default_address;
            $this->returnMsg['data']['other_address'] = $other_address_list;
        } else {
            $this->returnMsg['data']['default_address'] = [];
            $this->returnMsg['data']['other_address'] = [];
        }

        $this->setReturnMsg(0);
        return $this->returnMsg;
    }




}
