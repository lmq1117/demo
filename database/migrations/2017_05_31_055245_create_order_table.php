<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no');//订单号
            $table->string('order_desc');//订单描述
            $table->string('remark');//订单备注
            $table->integer('u_id');//会员id 谁下的单
            //0-3是正常订单流程 4-6进入退款流程
            //订单状态 0待付款，1已付款待发货，2已付款已发货，3已付款已收货，4客户申请退款，5客户申请退款并被审核，6退款完成
            $table->integer('status');
            $table->decimal('amount',10,2);//订单金额
            $table->decimal('discount',10,2);//折让金额
            $table->string('pay_type');//支付类型 1支付宝2微信...
            $table->string('third_pay_no');//支付流水
            $table->string('ship_type');//哪家快递1顺丰2圆通3申通4中通....
            $table->string('ship_no');//快递单号
            $table->timestamps();//下单时间  修改时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
