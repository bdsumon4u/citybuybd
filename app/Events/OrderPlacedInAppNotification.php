<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class OrderPlacedInAppNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order,
        public int $userId
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.placed';
    }

    public function broadcastWith(): array
    {
        $url = match ((int) $this->order->user?->role) {
            2 => route('manager.order.edit', ['id' => $this->order->id]),
            3 => route('employee.order.edit', ['id' => $this->order->id]),
            default => route('order.edit', ['id' => $this->order->id]),
        };

        return [
            'id' => $this->order->id,
            'title' => 'New Order Assigned',
            'body' => sprintf(
                'Order #%d has been placed for %s.',
                $this->order->id,
                $this->order->name ?? 'a customer'
            ),
            'url' => $url,
            'icon' => '/favicon.ico',
        ];
    }
}
