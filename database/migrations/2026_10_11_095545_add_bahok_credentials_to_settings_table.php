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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('bahok_client_id')->nullable();
            $table->string('bahok_client_secret')->nullable();
            $table->string('bahok_username')->nullable();
            $table->string('bahok_password')->nullable();
            $table->tinyInteger('bahok_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'bahok_client_id',
                'bahok_client_secret',
                'bahok_username',
                'bahok_password',
                'bahok_status',
            ]);
        });
    }
};
