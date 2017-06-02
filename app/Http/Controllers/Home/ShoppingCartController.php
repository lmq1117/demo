<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use App\User;
use App\Entity\Home\Goods;

class ShoppingCartController extends CommonController
{

    public function __construct()
    {
        parent::__construct();
    }

    //加入购物车
    public function addGoodsToShoppingCart(Request $request){
        //商品id
        //sessionid
        //商品数量
        $req_data = $request->all();
        $session_id = $req_data['session_id'];
        $u_id = $req_data['u_id'];
        $g_id = $req_data['g_id'];
        $num = $req_data['num'];//加入购物车的商品数量


        //将商品信息查出来，拼接上商品数量，加入购物车
        $goods = Goods::find($g_id);
        $goods->num = $num;
        $shoppingcart_data[] = $goods
        $this->session->set($session_id.'_shoppingcart',$shoppingcart_data);
        //if(){
        //
        //}




    }
}
