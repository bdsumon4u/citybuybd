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
        Schema::create('payroll_bonus_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_payroll_id')->constrained('monthly_payrolls')->onDelete('cascade');
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('editor_ip', 45)->nullable();
            $table->json('old_values');
            $table->json('new_values');
            $table->timestamps();

            $table->index(['monthly_payroll_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_bonus_audits');
    }
};
