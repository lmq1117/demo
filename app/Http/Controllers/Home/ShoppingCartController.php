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
        $appid = $req_data['username'];
        $user = User::findForId($appid);
        $u_id = $user->id;
        $g_id = isset($req_data['g_id']) ? $req_data['g_id'] : '';
        $cart_num = isset($req_data['num']) ? $req_data['num'] : '';//加入购物车的商品数量

        $session_id = "sessionid_" . $appid;
        $opearte = $req_data['operate'];

        $goods = Goods::find($g_id);
        $session_str = $this->session->get($session_id);
        $session = json_decode($session_str,true);
        switch ($opearte){
            case 'add':
                //购物车里边有该商品，增加商品数量
                //var_dump($session);exit;
                //if(isset($session['shopping_cart'][$g_id])){
                if(array_key_exists('shopping_cart',$session)){
                    if(array_key_exists($g_id,$session['shopping_cart'])){
                        //$goods = $session['shopping_cart'][$g_id];
                        //$old_cart_num = $goods->cart_num;

                        $session['shopping_cart'][$g_id]['cart_num'] = $session['shopping_cart'][$g_id]['cart_num'] + $cart_num;
                        //$goods['cart_num'] = $goods['cart_num'] + $cart_num;

                    }
                    else {
                        $goods->cart_num = $cart_num;
                        $session['shopping_cart'][$g_id] = $goods;
                    }
                } else {
                    $goods->cart_num = $cart_num;
                    $session['shopping_cart'][$g_id] = $goods;
                }

                //将商品信息查出来，拼接上商品数量，加入购物车
                //取出session 里边的购物车信息，
                //$this->session->set($session_id,json_encode($session));
                break;

            case 'edit':
                $goods = $session['shopping_cart'][$g_id];
                $goods['cart_num'] = $cart_num;
                $session['shopping_cart'][$g_id] = $goods;
                //$this->session->set($session_id,json_encode($session));
                break;
            case 'del':
                unset($session['shopping_cart'][$g_id]);
                //$this->session->set($session_id,json_encode($session));
                break;
            case 'list':
                if(isset($session['shopping_cart'])){
                    $this->returnMsg['data']['shopping_cart'] = $session['shopping_cart'];
                } else {
                    $this->returnMsg['data']['shopping_cart'] = [];
                }

        }
        $this->session->set($session_id,json_encode($session));
        $this->setReturnMsg(0);
        return $this->returnMsg;








    }
}
