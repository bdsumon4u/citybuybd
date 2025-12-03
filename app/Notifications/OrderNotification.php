<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\WhatsApp\Component;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplate;

class OrderNotification extends Notification
{
    use Queueable;

    protected string $templateName;

    /**
     * Create a new notification instance.
     *
     * @param string $templateName
     * @return void
     */
    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    /**
     * Get the WhatsApp representation of the notification.
     *
     * @param  \App\Models\Order  $notifiable
     * @return \NotificationChannels\WhatsApp\WhatsAppTemplate
     */
    public function toWhatsapp(Order $notifiable)
    {
        if (empty($notifiable->phone)) {
            return null;
        }

        // Format phone number: prepend +88 if it starts with 01
        $phone = preg_replace('/^01/', '+8801', $notifiable->phone);

        Log::info('WhatsApp notification phone: ' . $phone);
        Log::info('WhatsApp notification name: ' . $notifiable->name);
        Log::info('WhatsApp notification id: ' . $notifiable->id);

        return WhatsAppTemplate::create()
            ->name($this->templateName)
            ->language('bn')
            ->body(Component::text($notifiable->name))
            ->body(Component::text((string) $notifiable->id))
            ->to($phone);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
