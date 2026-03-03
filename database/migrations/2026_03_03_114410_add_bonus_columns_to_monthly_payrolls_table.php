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
        Schema::table('monthly_payrolls', function (Blueprint $table) {
            $table->decimal('hazira_bonus_amount', 10, 2)->default(0)->after('penalty_amount')->comment('Bonus for perfect attendance');
            $table->decimal('occasional_bonus_amount', 10, 2)->default(0)->after('hazira_bonus_amount')->comment('Festive/occasional bonuses');
            $table->decimal('xsell_bonus_amount', 10, 2)->default(0)->after('occasional_bonus_amount')->comment('Cross-sell bonus for over-delivered orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_payrolls', function (Blueprint $table) {
            $table->dropColumn(['hazira_bonus_amount', 'occasional_bonus_amount', 'xsell_bonus_amount']);
        });
    }
};
