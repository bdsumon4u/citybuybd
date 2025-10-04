<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->nullable();
            $table->string('thumb')->nullable();
            $table->string('image')->nullable();
            $table->string('gallery_images')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('stock')->nullable();
            $table->text('description')->nullable();
            $table->integer('category_id');
            $table->string('atr')->nullable();
            $table->string('atr_item')->nullable();
            $table->float('regular_price')->default(1);
            $table->float('offer_price')->nullable();
            $table->integer('status')->default(1)->comment('0=inactive,1=active');
            $table->string('assign')->nullable();
            
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
        Schema::dropIfExists('products');
    }
}
