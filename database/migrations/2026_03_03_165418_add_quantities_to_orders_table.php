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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('ordered_quantity')->default(0)->after('sub_total')->comment('Total quantity at order creation');
            $table->unsignedInteger('delivered_quantity')->nullable()->after('ordered_quantity')->comment('Total quantity delivered');
            $table->timestamp('delivered_at')->nullable()->after('delivered_quantity')->comment('When order was delivered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ordered_quantity', 'delivered_quantity', 'delivered_at']);
        });
    }
};
