<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsCollectionTable extends Migration
{
    /**
     * 创建商品收藏表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_collection', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('u_id');
            $table->integer('g_id');
            $table->integer('is_cancel')->default(0);//是否取消收藏  0 未取消收藏 1已取消收藏
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_collection');
    }
}
