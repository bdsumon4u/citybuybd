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
        Schema::table('users', function (Blueprint $table) {
            $table->time('panel_start')->nullable()->after('end_time');
            $table->time('panel_end')->nullable()->after('panel_start');
            $table->time('order_start')->nullable()->after('panel_end');
            $table->time('order_end')->nullable()->after('order_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['panel_start', 'panel_end', 'order_start', 'order_end']);
        });
    }
};
