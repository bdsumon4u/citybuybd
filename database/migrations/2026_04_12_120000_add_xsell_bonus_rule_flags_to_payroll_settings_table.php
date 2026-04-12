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
        Schema::table('payroll_settings', function (Blueprint $table): void {
            $table->boolean('xsell_bonus_on_quantity_increase')
                ->default(true)
                ->after('xsell_bonus_rate')
                ->comment('Give xSell bonus when delivered quantity exceeds ordered quantity');

            $table->boolean('xsell_bonus_on_product_replace')
                ->default(false)
                ->after('xsell_bonus_on_quantity_increase')
                ->comment('Give xSell bonus when delivered product set differs from originally ordered products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'xsell_bonus_on_quantity_increase',
                'xsell_bonus_on_product_replace',
            ]);
        });
    }
};
