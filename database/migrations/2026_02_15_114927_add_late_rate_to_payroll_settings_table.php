<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->decimal('latetime_rate', 10, 2)->default(0)->after('overtime_unit_minutes');
            $table->integer('latetime_unit_minutes')->default(60)->after('latetime_rate');
        });

        // Set default values for existing rows
        DB::table('payroll_settings')->update([
            'latetime_rate' => 0,
            'latetime_unit_minutes' => 60,
        ]);
    }

    public function down(): void
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->dropColumn(['latetime_rate', 'latetime_unit_minutes']);
        });
    }
};
