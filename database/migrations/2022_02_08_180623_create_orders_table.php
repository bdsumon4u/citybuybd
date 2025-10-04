<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_address')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('courier')->nullable();
            $table->string('city')->nullable();
            $table->string('pay')->nullable();
            $table->string('memo')->nullable();
            $table->string('zone')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('shipping_cost')->default(0)->nullable();
            $table->string('discount')->default(0)->nullable();
            $table->string('sub_total')->default(0)->nullable();
            $table->string('total')->default(0)->nullable();
            $table->string('order_assign')->nullable();
            $table->string('status')->default(1)->comment('');
            $table->text('order_note')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
