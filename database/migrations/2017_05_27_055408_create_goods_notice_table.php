<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_notice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('u_id');
            $table->integer('g_id');
            $table->integer('is_cancel');//是否取消关注
            $table->integer('is_push');//是否（价格变动后是否已经推送过消息）
            $table->dateTime('push_time');//推送时间
            $table->integer('pusher');//哪个管理员推送的 写管理员id
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
        Schema::dropIfExists('goods_notice');
    }
}
