<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_stock')->default(false)->after('stock')->index();
        });

        // Initialize is_stock = 1 for base products that already have a defined stock count
        DB::table('products')
            ->whereNull('base_id')
            ->whereNotNull('stock')
            ->where('stock', '!=', '')
            ->update(['is_stock' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_stock');
        });
    }
};
