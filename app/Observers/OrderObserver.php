<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderPlacedWebPushNotification;
use App\Services\WhatsAppService;
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
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order): void
    {
        $this->whatsAppService->sendOrderNotification($order);

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
            $this->whatsAppService->sendOrderNotification($order);
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
