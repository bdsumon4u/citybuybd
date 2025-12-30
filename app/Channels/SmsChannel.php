<?php

namespace App\Channels;

use App\Models\Settings;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);

        if (empty($message)) {
            return;
        }

        $phoneInput = $notifiable->phone;

        // Convert single string to array for uniform processing
        $phoneNumbers = is_array($phoneInput) ? $phoneInput : [$phoneInput];

        $formattedPhones = [];
        foreach ($phoneNumbers as $phoneItem) {
            $phone = preg_replace('/[^\d]/', '', (string) $phoneItem);

            // Format phone number: 8801...
            if (Str::startsWith($phone, '01')) {
                $phone = '88' . $phone;
            } elseif (Str::startsWith($phone, '+8801')) {
                $phone = Str::replaceFirst('+', '', $phone);
            }

            if (!empty($phone)) {
                $formattedPhones[] = $phone;
            }
        }

        if (empty($formattedPhones)) {
            return;
        }

        // Join multiple phone numbers with + separator, or use single phone
        $phone = implode('+', $formattedPhones);

        $settings = Settings::first();
        if (!$settings) {
            return;
        }

        $this->send_sms($settings, $phone, $message);
    }

    private function send_sms(Settings $settings, string $phone, string $message)
    {
        $provider = env('SMS_PROVIDER');

        // Determine if this is bulk SMS (multiple phone numbers joined with +)
        $isBulkSms = strpos($phone, '+') !== false;
        $label = $isBulkSms ? 'promotional' : 'transactional';

        if ($provider === 'ElitBuzz') {
            $url = 'https://msg.mram.com.bd/smsapi';
            $data = [
                'type' => 'text',
                'contacts' => $phone,
                'label' => $label,
                'api_key' => $settings->sms_api_key,
                'senderid' => $settings->sms_sender_id,
                'msg' => $message,
            ];
        } elseif ($provider === 'BDWebs') {
            $url = 'http://sms.bdwebs.com/smsapi';
            $data = [
                'type' => 'text',
                'contacts' => $phone,
                'label' => $label,
                'api_key' => $settings->sms_api_key,
                'senderid' => $settings->sms_sender_id,
                'msg' => $message,
            ];
        } else { // Hotash Tech
            Log::info('Hotash Tech - SMS sending started');
            $url = $settings->sms_api_url ?? 'https://sms.hotash.tech/api/v2/SendSMS';
            $data = [
                'ApiKey' => $settings->sms_api_key,
                'ClientId' => $settings->sms_api_secret,
                'SenderId' => $settings->sms_sender_id,
                'Message' => $message,
                'MobileNumbers' => $phone,
                'Is_Unicode' => 'true',
            ];
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->get($url, $data);
            if (!$response->successful()) {
                Log::error('SMS GET failed.', ['url' => $url, 'data' => $data, 'status' => $response->status(), 'body' => $response->body(), 'json' => $response->json()]);
            } else {
                Log::info('SMS sent successfully via GET. Response: ', $response->json());
            }
            return;
        }

        // if $data['api_key'] and $data['secret_key'] are not set,
        // then please set from config/services.php
        if (empty($data['api_key'])) {
            $data['api_key'] = config('services.sms.api_key');
        }
        if (empty($data['secret_key'])) {
            $data['secret_key'] = config('services.sms.secret_key');
        }

        Log::info('Sending SMS via Channel:', ['provider' => $provider, 'url' => $url, 'data' => $data]);

        try {
            $response = Http::withoutVerifying()->post($url, $data);

            if ($response->successful()) {
                Log::info('SMS sent successfully. Response: ' . $response->body());
            } else {
                Log::warning('SMS POST failed, trying GET.', ['status' => $response->status(), 'body' => $response->body()]);
                $response = Http::withoutVerifying()->get($url, $data);
                if (!$response->successful()) {
                    Log::error('SMS GET also failed.', ['status' => $response->status(), 'body' => $response->body()]);
                } else {
                    Log::info('SMS sent successfully via GET. Response: ' . $response->body());
                }
            }

        } catch (\Exception $e) {
            Log::error('SMS Exception: ' . $e->getMessage());
        }
    }
}

