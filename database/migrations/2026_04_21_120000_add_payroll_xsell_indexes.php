<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->index(['order_assign', 'status', 'delivered_at'], 'orders_assign_status_delivered_at_idx');
        });

        Schema::table('carts', function (Blueprint $table): void {
            $table->index(['order_id', 'product_id'], 'carts_order_product_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex('orders_assign_status_delivered_at_idx');
        });

        Schema::table('carts', function (Blueprint $table): void {
            $table->dropIndex('carts_order_product_idx');
        });
    }
};
