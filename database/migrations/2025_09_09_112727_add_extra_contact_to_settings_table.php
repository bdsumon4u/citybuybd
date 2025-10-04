<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('dial_up')->nullable()->after('phone_three');
            $table->string('whatsapp_number')->nullable()->after('dial_up');
            $table->string('messenger_username')->nullable()->after('whatsapp_number');
            $table->string('imo_number')->nullable()->after('messenger_username');
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['dial_up', 'whatsapp_number', 'messenger_username', 'imo_number']);
        });
    }
};
