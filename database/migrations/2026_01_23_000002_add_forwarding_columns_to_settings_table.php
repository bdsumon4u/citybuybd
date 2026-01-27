<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('forwarding_enabled')->default(false)->after('orders_per_day_limit');
            $table->string('forwarding_master_domain')->nullable()->after('forwarding_enabled');
            $table->string('forwarding_master_secret')->nullable()->after('forwarding_master_domain');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'forwarding_enabled',
                'forwarding_master_domain',
                'forwarding_master_secret',
            ]);
        });
    }
};
