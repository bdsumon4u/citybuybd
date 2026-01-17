<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class OrderPlacedWebPushNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Order $order
    ) {}

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification = null): WebPushMessage
    {
        $title = 'New Order Assigned';

        $body = sprintf(
            'Order #%d has been placed for %s.',
            $this->order->id,
            $this->order->name ?? 'a customer'
        );

        $url = match ((int) $notifiable->role) {
            2 => route('manager.order.edit', ['id' => $this->order->id]),
            3 => route('employee.order.edit', ['id' => $this->order->id]),
            default => route('order.edit', ['id' => $this->order->id]),
        };

        Log::info('Sending web push notification to user: '.$notifiable->id);
        Log::info('Title: '.$title);
        Log::info('Body: '.$body);
        Log::info('URL: '.$url);

        return (new WebPushMessage)
            ->title($title)
            ->icon('/favicon.ico')
            ->body($body)
            ->data(['url' => $url])
            ->action('View Order', $url);
    }
}
