<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('attribute')->nullable();
            $table->timestamps();
            // $table->foreign('user_id')->reference('id')->on('users')->onDelete('casecade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
