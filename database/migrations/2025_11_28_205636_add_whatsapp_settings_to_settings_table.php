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
            $table->string('whatsapp_from_phone_number_id')->nullable()->after('qc_token');
            $table->text('whatsapp_token')->nullable()->after('whatsapp_from_phone_number_id');

            // WhatsApp notification settings for each order status (using snake_case status names)
            $table->boolean('whatsapp_notification_enabled_processing')->default(0)->after('whatsapp_token');
            $table->string('whatsapp_template_name_processing')->nullable()->after('whatsapp_notification_enabled_processing');

            $table->boolean('whatsapp_notification_enabled_pending_delivery')->default(0)->after('whatsapp_template_name_processing');
            $table->string('whatsapp_template_name_pending_delivery')->nullable()->after('whatsapp_notification_enabled_pending_delivery');

            $table->boolean('whatsapp_notification_enabled_on_hold')->default(0)->after('whatsapp_template_name_pending_delivery');
            $table->string('whatsapp_template_name_on_hold')->nullable()->after('whatsapp_notification_enabled_on_hold');

            $table->boolean('whatsapp_notification_enabled_cancel')->default(0)->after('whatsapp_template_name_on_hold');
            $table->string('whatsapp_template_name_cancel')->nullable()->after('whatsapp_notification_enabled_cancel');

            $table->boolean('whatsapp_notification_enabled_completed')->default(0)->after('whatsapp_template_name_cancel');
            $table->string('whatsapp_template_name_completed')->nullable()->after('whatsapp_notification_enabled_completed');

            $table->boolean('whatsapp_notification_enabled_pending_payment')->default(0)->after('whatsapp_template_name_completed');
            $table->string('whatsapp_template_name_pending_payment')->nullable()->after('whatsapp_notification_enabled_pending_payment');

            $table->boolean('whatsapp_notification_enabled_on_delivery')->default(0)->after('whatsapp_template_name_pending_payment');
            $table->string('whatsapp_template_name_on_delivery')->nullable()->after('whatsapp_notification_enabled_on_delivery');

            $table->boolean('whatsapp_notification_enabled_no_response1')->default(0)->after('whatsapp_template_name_on_delivery');
            $table->string('whatsapp_template_name_no_response1')->nullable()->after('whatsapp_notification_enabled_no_response1');

            $table->boolean('whatsapp_notification_enabled_no_response2')->default(0)->after('whatsapp_template_name_no_response1');
            $table->string('whatsapp_template_name_no_response2')->nullable()->after('whatsapp_notification_enabled_no_response2');

            $table->boolean('whatsapp_notification_enabled_courier_hold')->default(0)->after('whatsapp_template_name_no_response2');
            $table->string('whatsapp_template_name_courier_hold')->nullable()->after('whatsapp_notification_enabled_courier_hold');

            $table->boolean('whatsapp_notification_enabled_order_return')->default(0)->after('whatsapp_template_name_courier_hold');
            $table->string('whatsapp_template_name_order_return')->nullable()->after('whatsapp_notification_enabled_order_return');
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
            $table->dropColumn([
                'whatsapp_from_phone_number_id',
                'whatsapp_token',
                'whatsapp_notification_enabled_processing',
                'whatsapp_template_name_processing',
                'whatsapp_notification_enabled_pending_delivery',
                'whatsapp_template_name_pending_delivery',
                'whatsapp_notification_enabled_on_hold',
                'whatsapp_template_name_on_hold',
                'whatsapp_notification_enabled_cancel',
                'whatsapp_template_name_cancel',
                'whatsapp_notification_enabled_completed',
                'whatsapp_template_name_completed',
                'whatsapp_notification_enabled_pending_payment',
                'whatsapp_template_name_pending_payment',
                'whatsapp_notification_enabled_on_delivery',
                'whatsapp_template_name_on_delivery',
                'whatsapp_notification_enabled_no_response1',
                'whatsapp_template_name_no_response1',
                'whatsapp_notification_enabled_no_response2',
                'whatsapp_template_name_no_response2',
                'whatsapp_notification_enabled_courier_hold',
                'whatsapp_template_name_courier_hold',
                'whatsapp_notification_enabled_order_return',
                'whatsapp_template_name_order_return',
            ]);
        });
    }
};
