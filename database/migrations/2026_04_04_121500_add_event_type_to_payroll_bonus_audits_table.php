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
        Schema::table('payroll_bonus_audits', function (Blueprint $table) {
            $table->string('event_type', 30)->default('manual_edit')->after('editor_ip');
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_bonus_audits', function (Blueprint $table) {
            $table->dropIndex(['event_type']);
            $table->dropColumn('event_type');
        });
    }
};
