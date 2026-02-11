<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('overtime_rate', 10, 2)->default(0); // amount per overtime unit
            $table->integer('overtime_unit_minutes')->default(60); // how many minutes = 1 unit
            $table->decimal('forgot_checkout_penalty', 10, 2)->default(0); // penalty for not checking out
            $table->timestamps();
        });

        // Insert default row
        DB::table('payroll_settings')->insert([
            'overtime_rate' => 50,
            'overtime_unit_minutes' => 60,
            'forgot_checkout_penalty' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_settings');
    }
};
