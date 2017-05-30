<?php

namespace App\Entity\Home;

use Illuminate\Database\Eloquent\Model;

class GoodsNotice extends Model
{
    //
    protected $table = 'goods_notice';

    //商品id对应的关注的用户数
    public function countUser($gid){
        return $this->where('is_cancel','!=',1)->where('g_id',$gid)->count();
    }
}
