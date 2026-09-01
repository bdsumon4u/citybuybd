<?php

use App\Models\ManualOrderType;
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
        if (! Schema::hasColumn('orders', 'created_by')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->unsignedBigInteger('created_by')->nullable()->after('order_assign')->index();
            });
        }

        if (! Schema::hasColumn('payroll_settings', 'manual_order_bonus_rate')) {
            Schema::table('payroll_settings', function (Blueprint $table): void {
                $table->decimal('manual_order_bonus_rate', 10, 2)->default(5.00)->after('xsell_bonus_rate');
            });
        }

        if (! Schema::hasColumn('monthly_payrolls', 'manual_order_bonus_amount')) {
            Schema::table('monthly_payrolls', function (Blueprint $table): void {
                $table->decimal('manual_order_bonus_amount', 10, 2)->default(0.00)->after('xsell_bonus_amount');
            });
        }

        // Backfill existing genuine staff manual orders (excluding online, landing, and domain orders)
        try {
            $excludedTypes = ['online', 'incomplete', 'Landing', 'unmuktobazar.com', 'www.unmuktobazar.com', 'lp.satrongexpress.com', 'shoperdeal.com', 'slave1.citybuybd.com', 'slave2.citybuybd.com'];
            $manualTypes = ManualOrderType::whereNotIn('name', $excludedTypes)->pluck('name')->merge(['manual'])->unique()->filter()->values()->toArray();
            
            DB::table('orders')
                ->whereNull('created_by')
                ->whereIn('order_type', $manualTypes)
                ->whereNotIn('order_type', $excludedTypes)
                ->where('order_type', 'not like', '%.com%')
                ->where('order_type', 'not like', '%.net%')
                ->where('order_type', 'not like', '%.org%')
                ->whereNotNull('order_assign')
                ->update(['created_by' => DB::raw('order_assign')]);
        } catch (\Throwable $e) {
            // Ignore if tables are empty or during testing
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'created_by')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropIndex(['created_by']);
                $table->dropColumn('created_by');
            });
        }

        if (Schema::hasColumn('payroll_settings', 'manual_order_bonus_rate')) {
            Schema::table('payroll_settings', function (Blueprint $table): void {
                $table->dropColumn('manual_order_bonus_rate');
            });
        }

        if (Schema::hasColumn('monthly_payrolls', 'manual_order_bonus_amount')) {
            Schema::table('monthly_payrolls', function (Blueprint $table): void {
                $table->dropColumn('manual_order_bonus_amount');
            });
        }
    }
};
