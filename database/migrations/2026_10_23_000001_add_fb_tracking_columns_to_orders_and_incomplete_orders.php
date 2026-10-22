<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('orders', 'fbp')) {
                    $table->string('fbp')->nullable()->after('utm_campaign');
                }
                if (! Schema::hasColumn('orders', 'fbc')) {
                    $table->text('fbc')->nullable()->after('fbp');
                }
            });
        }

        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('incomplete_orders', 'fbp')) {
                    $table->string('fbp')->nullable()->after('utm_campaign');
                }
                if (! Schema::hasColumn('incomplete_orders', 'fbc')) {
                    $table->text('fbc')->nullable()->after('fbp');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                $columns = array_filter(['fbp', 'fbc'], fn ($col) => Schema::hasColumn('orders', $col));
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table): void {
                $columns = array_filter(['fbp', 'fbc'], fn ($col) => Schema::hasColumn('incomplete_orders', $col));
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
