<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incomplete_orders', function (Blueprint $table): void {
            $table->string('utm_source')->nullable()->after('ip_address');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
        });
    }

    public function down(): void
    {
        Schema::table('incomplete_orders', function (Blueprint $table): void {
            $table->dropColumn([
                'utm_source',
                'utm_medium',
                'utm_campaign',
            ]);
        });
    }
};
