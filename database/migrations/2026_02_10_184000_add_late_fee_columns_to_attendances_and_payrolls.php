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
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('late_minutes')->default(0)->after('overtime_minutes');
        });

        Schema::table('monthly_payrolls', function (Blueprint $table) {
            $table->decimal('late_deduction', 10, 2)->default(0)->after('overtime_amount');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('late_minutes');
        });

        Schema::table('monthly_payrolls', function (Blueprint $table) {
            $table->dropColumn('late_deduction');
        });
    }
};
