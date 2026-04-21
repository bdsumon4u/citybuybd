<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (Schema::hasColumn('orders', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });

        Schema::table('incomplete_orders', function (Blueprint $table): void {
            if (Schema::hasColumn('incomplete_orders', 'campaign_id')) {
                $table->dropColumn('campaign_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'campaign_id')) {
                $table->string('campaign_id')->nullable()->after('utm_campaign');
            }
        });

        Schema::table('incomplete_orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('incomplete_orders', 'campaign_id')) {
                $table->string('campaign_id')->nullable()->after('utm_campaign');
            }
        });
    }
};
