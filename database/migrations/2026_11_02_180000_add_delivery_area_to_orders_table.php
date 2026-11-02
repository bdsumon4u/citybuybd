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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('orders', 'delivery_area')) {
                    $table->string('delivery_area', 50)->default('outside_dhaka')->after('address');
                    $table->index('delivery_area');
                }
            });

            // Set all existing orders as outside dhaka orders
            DB::table('orders')
                ->whereNull('delivery_area')
                ->orWhere('delivery_area', '')
                ->update(['delivery_area' => 'outside_dhaka']);
        }

        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('incomplete_orders', 'delivery_area')) {
                    $table->string('delivery_area', 50)->default('outside_dhaka')->after('address');
                }
            });

            DB::table('incomplete_orders')
                ->whereNull('delivery_area')
                ->orWhere('delivery_area', '')
                ->update(['delivery_area' => 'outside_dhaka']);
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table): void {
                if (! Schema::hasColumn('settings', 'inside_dhaka_courier_id')) {
                    $table->unsignedBigInteger('inside_dhaka_courier_id')->nullable();
                }
                if (! Schema::hasColumn('settings', 'outside_dhaka_courier_id')) {
                    $table->unsignedBigInteger('outside_dhaka_courier_id')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if (Schema::hasColumn('orders', 'delivery_area')) {
                    $table->dropIndex(['delivery_area']);
                    $table->dropColumn('delivery_area');
                }
            });
        }

        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table): void {
                if (Schema::hasColumn('incomplete_orders', 'delivery_area')) {
                    $table->dropColumn('delivery_area');
                }
            });
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table): void {
                $columns = array_filter(
                    ['inside_dhaka_courier_id', 'outside_dhaka_courier_id'],
                    fn ($col) => Schema::hasColumn('settings', $col)
                );
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
