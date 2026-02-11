<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('daily_salary', 10, 2)->default(0)->after('end_time');
            $table->string('off_days')->nullable()->after('daily_salary'); // comma separated e.g. "Friday,Saturday"
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_salary', 'off_days']);
        });
    }
};
