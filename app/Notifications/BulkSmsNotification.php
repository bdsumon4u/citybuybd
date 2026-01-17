<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class BulkSmsNotification extends Notification
{
    use Queueable;

    public function __construct(private string $message, private ?string $customerName = null) {}

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
