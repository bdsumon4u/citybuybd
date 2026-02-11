<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->integer('total_days')->default(0); // total days in month
            $table->integer('working_days')->default(0); // total days minus off days
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('off_day_presents')->default(0); // days worked on off days
            $table->decimal('daily_salary', 10, 2)->default(0); // snapshot of daily salary
            $table->decimal('base_salary', 10, 2)->default(0); // daily_salary * present_days
            $table->decimal('off_day_bonus', 10, 2)->default(0); // extra 0.5x for off day work
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->decimal('advance_deduction', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->string('status')->default('draft'); // draft, approved, paid
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_payrolls');
    }
};
