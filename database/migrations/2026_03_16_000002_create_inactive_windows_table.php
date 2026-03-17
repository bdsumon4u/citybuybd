<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inactive_windows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('inactive_from');
            $table->timestamp('inactive_until');
            $table->unsignedInteger('duration_minutes');
            $table->timestamps();

            $table->index(['user_id', 'inactive_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inactive_windows');
    }
};
