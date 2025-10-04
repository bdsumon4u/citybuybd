<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('incomplete_orders', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('last_activity_at'); 
            $table->timestamp('completed_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('incomplete_orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'completed_at']);
        });
    }

};
