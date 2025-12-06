<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class BulkSmsNotification extends Notification
{
    use Queueable;

    private string $message;
    private ?string $customerName;

    public function __construct(
        string $message,
        ?string $customerName = null
    ) {
        $this->message = $message;
        $this->customerName = $customerName;
    }

    public function via($notifiable): array
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable): string
    {
        $message = $this->message;

        if ($this->customerName) {
            $message = str_replace('{name}', $this->customerName, $message);
        }

        return $message;
    }
}
