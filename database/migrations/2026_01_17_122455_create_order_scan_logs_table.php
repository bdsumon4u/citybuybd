<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_scan_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->string('scan_type'); // 'handover' or 'return'
            $table->unsignedBigInteger('scanned_by')->nullable(); // user id who scanned
            $table->timestamp('scanned_at');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index(['scan_type', 'scanned_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_scan_logs');
    }
};
