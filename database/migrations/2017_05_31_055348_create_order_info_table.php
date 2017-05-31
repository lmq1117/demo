<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_info', function (Blueprint $table) {
            $table->increments('id');//商品详情id
            $table->integer('o_id');//哪个订单
            $table->integer('g_id');//哪个商品
            $table->integer('num');//买了几个
            $table->decimal('price',10,2);//商品单价
            $table->string('goods_name');//商品名
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
        Schema::dropIfExists('order_info');
    }
}
