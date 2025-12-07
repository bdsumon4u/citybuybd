<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\OrderPlacedInAppNotification;
use App\Models\Order;
use App\Notifications\OrderNotification;
use App\Notifications\OrderPlacedWebPushNotification;
use App\Services\WhatsAppService;
use App\Models\Settings;
use App\Channels\SmsChannel;
use Illuminate\Support\Facades\Log;

final class OrderObserver
{
    /**
     * @var WhatsAppService
     */
    private WhatsAppService $whatsAppService;

    /**
     * @param WhatsAppService $whatsAppService
     */
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Helper to check if SMS is enabled for status
     */
    private function isSmsEnabled(Order $order): bool
    {
        if (!$order->status) return false;

        $statusName = $order->getStatusName();
        if (!$statusName) return false;

        $settings = Settings::first();
        if (!$settings) return false;

        $enabledField = 'sms_notification_enabled_' . $statusName;
        return (bool) $settings->$enabledField;
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order): void
    {
        // 1. Send WhatsApp (handled by Service which wraps OrderNotification)
        $this->whatsAppService->sendOrderNotification($order);

        // 2. Send SMS if enabled
        if ($this->isSmsEnabled($order)) {
             // We reuse OrderNotification but only with SmsChannel
             // Template name is required by constructor but unused by toSms, so we pass empty or dummy
             $order->notify(new OrderNotification('sms_dummy', [SmsChannel::class]));
        }

        Log::info('Order created: ' . $order->id);
        if ($order->user) {
            Log::info('Sending web push notification to user: ' . $order->user->id);
            $order->user->notify(new OrderPlacedWebPushNotification($order));

            // Broadcast in-app notification via Ably
            broadcast(new OrderPlacedInAppNotification($order, $order->user->id));
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
            $this->whatsAppService->sendOrderNotification($order);

            if ($this->isSmsEnabled($order)) {
                 $order->notify(new OrderNotification('sms_dummy', [SmsChannel::class]));
            }
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
