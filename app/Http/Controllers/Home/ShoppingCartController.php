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

        //公共信息
        $session_id = $req_data['session_id'];
        $u_id = $req_data['u_id'];
        $g_id = $req_data['g_id'];
        $cart_num = $req_data['num'];//加入购物车的商品数量

        $act = $req_data['act'];

        switch ($act){
            case 'add':

                $goods = Goods::find($g_id);
                $session = $this->session->get($session_id);

                //购物车里边有该商品，增加商品数量
                if($session['shopping_cart'][$g_id]){
                    $goods = $session['shopping_cart'][$g_id];
                    //$old_cart_num = $goods->cart_num;
                    $goods->cart_num = $goods->cart_num + $cart_num;

                } else {
                    $goods->cart_num = $cart_num;
                    $session['shopping_cart'][$g_id] = $goods;
                }
                //将商品信息查出来，拼接上商品数量，加入购物车
                //取出session 里边的购物车信息，

                $this->session->set($session_id,$session);
                break;

        }








    }
}
