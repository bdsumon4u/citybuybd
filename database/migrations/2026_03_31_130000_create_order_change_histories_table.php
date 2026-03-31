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
        Schema::create('order_change_histories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('field_name', 50);
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->string('source', 120)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->index(['order_id', 'changed_at']);
            $table->index(['field_name', 'changed_at']);

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_change_histories');
    }
};
