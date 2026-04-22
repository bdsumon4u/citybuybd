<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->string('fb_pixel_id')->nullable()->after('fb_pixel');
            $table->text('fb_access_token')->nullable()->after('fb_pixel_id');
            $table->string('fb_test_event_code')->nullable()->after('fb_access_token');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'fb_pixel_id',
                'fb_access_token',
                'fb_test_event_code',
            ]);
        });
    }
};
