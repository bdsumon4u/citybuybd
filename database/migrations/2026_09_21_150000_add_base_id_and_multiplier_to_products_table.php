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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'base_id')) {
                $table->unsignedInteger('base_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('products', 'base_multiplier')) {
                $table->unsignedInteger('base_multiplier')->default(1)->after('base_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'base_multiplier')) {
                $table->dropColumn('base_multiplier');
            }
            if (Schema::hasColumn('products', 'base_id')) {
                $table->dropColumn('base_id');
            }
        });
    }
};
