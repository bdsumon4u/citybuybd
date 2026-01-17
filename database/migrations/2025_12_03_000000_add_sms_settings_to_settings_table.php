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
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('sms_api_key')->nullable();
            $table->string('sms_api_secret')->nullable();
            $table->string('sms_sender_id')->nullable();
            $table->string('sms_api_url')->nullable();

            $statuses = [
                'processing',
                'pending_delivery',
                'on_hold',
                'cancel',
                'completed',
                'pending_payment',
                'on_delivery',
                'no_response1',
                'no_response2',
                'courier_hold',
                'order_return',
            ];

            foreach ($statuses as $status) {
                $table->boolean('sms_notification_enabled_'.$status)->default(false);
                $table->text('sms_template_'.$status)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['sms_api_key', 'sms_api_secret', 'sms_sender_id', 'sms_api_url']);

            $statuses = [
                'processing',
                'pending_delivery',
                'on_hold',
                'cancel',
                'completed',
                'pending_payment',
                'on_delivery',
                'no_response1',
                'no_response2',
                'courier_hold',
                'order_return',
            ];

            foreach ($statuses as $status) {
                $table->dropColumn('sms_notification_enabled_'.$status);
                $table->dropColumn('sms_template_'.$status);
            }
        });
    }
};
