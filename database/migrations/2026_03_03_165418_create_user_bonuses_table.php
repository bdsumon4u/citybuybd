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
        Schema::create('user_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->comment('Bonus name (e.g., Eid Bonus, Individual Incentive)');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->comment('Bonus amount in taka');
            $table->year('year');
            $table->string('month')->comment('Month in MM format (01-12)');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bonuses');
    }
};
