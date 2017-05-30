<?php

namespace App\Http\Controllers;

use App\Entity\Home\GoodsCollection;
use Illuminate\Http\Request;

class GoodsCollectionController extends Controller
{
    //用户添加商品收藏
    public function goodsCollectionAdd(Request $request){

        $data = $request->all();
        $goods_collection = new GoodsCollection();
        $goods_collection->g_id = $data['g_id'];
        $goods_collection->u_id = $data['u_id'];
        //$goods_collection->u_id = $data['u_id'];
    }
}
