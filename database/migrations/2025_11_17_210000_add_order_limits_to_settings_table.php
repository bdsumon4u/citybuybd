<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'orders_per_hour_limit')) {
                $table->unsignedInteger('orders_per_hour_limit')->nullable()->after('ip_block');
            }
            if (! Schema::hasColumn('settings', 'orders_per_day_limit')) {
                $table->unsignedInteger('orders_per_day_limit')->nullable()->after('orders_per_hour_limit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'orders_per_hour_limit')) {
                $table->dropColumn('orders_per_hour_limit');
            }
            if (Schema::hasColumn('settings', 'orders_per_day_limit')) {
                $table->dropColumn('orders_per_day_limit');
            }
        });
    }
};
