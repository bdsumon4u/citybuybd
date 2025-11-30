<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;
use App\Models\Settings;
use App\Notifications\OrderNotification;
use App\Notifications\OrderPlacedWebPushNotification;
use Illuminate\Support\Facades\Log;

final class OrderObserver
{
    /**
     * Map order status number to snake_case name
     *
     * @param int $status
     * @return string|null
     */
    private function getStatusName(int $status): ?string
    {
        $statusMap = [
            1 => 'processing',
            2 => 'pending_delivery',
            3 => 'on_hold',
            4 => 'cancel',
            5 => 'completed',
            6 => 'pending_payment',
            7 => 'on_delivery',
            8 => 'no_response1',
            9 => 'no_response2',
            11 => 'courier_hold',
            12 => 'order_return',
        ];

        return $statusMap[$status] ?? null;
    }

    /**
     * Send WhatsApp notification if enabled for the status
     *
     * @param Order $order
     * @return void
     */
    private function sendWhatsAppNotification(Order $order): void
    {
        if (!$order->status) {
            return;
        }

        $statusName = $this->getStatusName((int) $order->status);
        if (!$statusName) {
            return;
        }

        $settings = Settings::first();
        if (!$settings) {
            return;
        }

        // Check if WhatsApp token and phone number ID are configured
        if (empty($settings->whatsapp_token) || empty($settings->whatsapp_from_phone_number_id)) {
            return;
        }

        // Check if notification is enabled for this status
        $enabledField = 'whatsapp_notification_enabled_' . $statusName;
        if (!$settings->$enabledField) {
            return;
        }

        // Get template name for this status, use status name as default if not configured
        $templateField = 'whatsapp_template_name_' . $statusName;
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

        // Send notification
        try {
            $order->notify(new OrderNotification($templateName));
        } catch (\Exception $e) {
            // Log error but don't break the order creation/update
            Log::error('WhatsApp notification failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'status' => $order->status,
                'template' => $templateName,
            ]);
        }
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order): void
    {
        $this->sendWhatsAppNotification($order);

        Log::info('Order created: ' . $order->id);
        if ($order->user) {
            Log::info('Sending web push notification to user: ' . $order->user->id);
            $order->user->notify(new OrderPlacedWebPushNotification($order));
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order): void
    {
        // Only send notification if status was changed
        if ($order->wasChanged('status')) {
            $this->sendWhatsAppNotification($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
