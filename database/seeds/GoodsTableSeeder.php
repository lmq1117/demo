<?php

use Illuminate\Database\Seeder;
use App\Entity\Home\Goods;

class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //执行一次seeder 插入10条数据
        for ($i = 1 ; $i <=1000; $i ++)
        {
            $goods = new Goods;
            $goods->goods_sn = "goods_sn_{$i}";
            $goods->goods_name = "商品名称$i";
            $goods->goods_desc = "商品描述$i";
            $goods->goods_img = "http://img.lmqde.com/goods_main/{$goods->goods_sn}.jpg";
            $goods->goods_num = 3000;
            $goods->goods_price = mt_rand(499,1000);
            $goods->is_promotion = mt_rand(0,1);
            $goods->is_on_sale = mt_rand(0,1);
            $goods->save();




        }
    }
}
