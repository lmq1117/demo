<?php

namespace App\Entity\Home;

use Illuminate\Database\Eloquent\Model;

class GoodsCollection extends Model
{
    //
    protected $table = 'goods_collection';


    //商品id对应的收藏的用户数
    public function countUser($gid){
        return $this->where('is_cancel','!=',1)->where('g_id',$gid)->count();
    }

    //
}
