<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Settings;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

final class WhatsAppService
{
    /**
     * Send WhatsApp notification if enabled for the status
     */
    public function sendOrderNotification(Order $order): void
    {
        if (! $order->status) {
            return;
        }

        $statusName = $order->getStatusName();
        if (! $statusName) {
            return;
        }

        $settings = Settings::first();
        if (! $settings) {
            return;
        }

        // Check if WhatsApp token and phone number ID are configured
        if (empty($settings->whatsapp_token) || empty($settings->whatsapp_from_phone_number_id)) {
            return;
        }

        // Check if notification is enabled for this status
        $enabledField = 'whatsapp_notification_enabled_'.$statusName;
        if (! $settings->$enabledField) {
            return;
        }

        // Get template name for this status, use status name as default if not configured
        $templateField = 'whatsapp_template_name_'.$statusName;
        $templateName = $settings->$templateField;

        // Use snake_case status name as default template name if not configured
        if (empty($templateName)) {
            $templateName = $statusName;
        }

        // Set WhatsApp config from database settings
        config([
            'services.whatsapp.from-phone-number-id' => $settings->whatsapp_from_phone_number_id,
            'services.whatsapp.token' => $settings->whatsapp_token,
        ]);

        // Re-bind WhatsAppCloudApi with fresh config because the ServiceProvider binds it with boot-time config
        $config = [
            'from_phone_number_id' => $settings->whatsapp_from_phone_number_id,
            'access_token' => $settings->whatsapp_token,
            'graph_version' => config('services.whatsapp.graph_version', WhatsAppCloudApi::DEFAULT_GRAPH_VERSION),
            'timeout' => config('services.whatsapp.timeout'),
        ];

        app()->bind(WhatsAppCloudApi::class, fn () => new WhatsAppCloudApi($config));

        // Send notification
        try {
            $order->notify(new OrderNotification($templateName));
        } catch (\Exception $e) {
            // Log error but don't break the order creation/update
            Log::error('WhatsApp notification failed: '.$e->getMessage(), [
                'order_id' => $order->id,
                'status' => $order->status,
                'template' => $templateName,
            ]);
        }
    }
}
