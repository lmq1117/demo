<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('goods_id');
            $table->string('goods_sn');
            $table->string('goods_name');
            $table->text('goods_desc');
            $table->string('goods_img');
            $table->decimal('goods_price',10,2);
            $table->integer('goods_num');
            $table->integer('is_promotion');
            $table->integer('is_on_sale');
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
        Schema::dropIfExists('goods');
    }
}
