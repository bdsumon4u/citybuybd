<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\WhatsApp\Component;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplate;

class OrderNotification extends Notification
{
    use Queueable;

    protected array $channels;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected string $templateName, array $channels = [])
    {
        $this->channels = $channels ?: [WhatsAppChannel::class];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->channels;
    }

    /**
     * Get the WhatsApp representation of the notification.
     *
     * @return \NotificationChannels\WhatsApp\WhatsAppTemplate|null
     */
    public function toWhatsapp(Order $notifiable)
    {
        if (empty($notifiable->phone)) {
            return null;
        }

        // Format phone number: prepend +88 if it starts with 01
        $phone = preg_replace('/^01/', '+8801', (string) $notifiable->phone);

        Log::info('WhatsApp notification phone: '.$phone);
        Log::info('WhatsApp notification name: '.$notifiable->name);
        Log::info('WhatsApp notification id: '.$notifiable->id);

        $template = WhatsAppTemplate::create()
            ->name($this->templateName)
            ->language('bn');

        foreach (Order::getTemplateVariables($notifiable) as $variable) {
            $template->body(Component::text($variable));
        }

        return $template->to($phone);
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @return string|null
     */
    public function toSms(Order $notifiable)
    {
        if (empty($notifiable->phone)) {
            return null;
        }

        $settings = Settings::first();
        if (! $settings) {
            return null;
        }

        $statusName = $notifiable->getStatusName();
        if (! $statusName) {
            return null;
        }

        $templateField = 'sms_template_'.$statusName;
        $template = $settings->$templateField;

        if (empty($template)) {
            return null;
        }

        return str_replace(
            ['{name}', '{order_id}', '{product_details}', '{amount}'],
            [$notifiable->name, $notifiable->id, $notifiable->getProductDetailsVariable(), $notifiable->total],
            $template
        );
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
