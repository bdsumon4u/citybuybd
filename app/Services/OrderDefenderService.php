<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

class OrderDefenderService
{
    public function defend(Request $request, Settings $settings): ?array
    {
        $normalizedPhone = $this->normalizePhone((string) $request->input('phone', ''));

        $blockNumber = $this->csvList((string) $settings->number_block);
        if (in_array((string) $request->phone, $blockNumber, true)) {
            return $this->suspiciousOrderNotification();
        }

        $blockIP = $this->csvList((string) $settings->ip_block);
        if (in_array((string) $request->ip(), $blockIP, true)) {
            return $this->suspiciousOrderNotification();
        }

        $fingerprint = $this->buildDeviceFingerprint(
            $this->resolveDeviceId($request),
            $request->hasSession() ? $request->session()->getId() : null,
            (string) $request->ip(),
            $request->userAgent(),
            $request,
        );

        $hourLimit = (int) ($settings->orders_per_hour_limit ?? 0);
        $dayLimit = (int) ($settings->orders_per_day_limit ?? 0);

        if ($normalizedPhone !== '') {
            if ($hourLimit > 0) {
                $phoneHourKey = 'order_rate:hour:phone:'.$normalizedPhone.':'.Date::now()->format('YmdH');
                Cache::add($phoneHourKey, 0, Date::now()->addHour());
                $currentPhoneHourCount = Cache::increment($phoneHourKey);

                if ($currentPhoneHourCount > $hourLimit) {
                    return [
                        'message' => 'আপনি স্বল্প সময়ে অনেকগুলো অর্ডারের চেষ্টা করেছেন। আপনার একটি অর্ডার আগেই গ্রহণ করা হয়েছে; আপনি আরও অর্ডার করতে চাইলে আমাদের হেল্পলাইন নম্বরে যোগাযোগ করুন।',
                        'alert-type' => 'danger',
                    ];
                }

                $recentPhoneHourOrders = Order::query()
                    ->where('phone', $request->phone)
                    ->where('created_at', '>=', Date::now()->subHour())
                    ->count();

                if ($recentPhoneHourOrders >= $hourLimit) {
                    return [
                        'message' => 'আপনি স্বল্প সময়ে অনেকগুলো অর্ডারের চেষ্টা করেছেন। আপনার একটি অর্ডার আগেই গ্রহণ করা হয়েছে; আপনি আরও অর্ডার করতে চাইলে আমাদের হেল্পলাইন নম্বরে যোগাযোগ করুন।',
                        'alert-type' => 'danger',
                    ];
                }
            }

            if ($dayLimit > 0) {
                $phoneDayKey = 'order_rate:day:phone:'.$normalizedPhone.':'.Date::now()->format('Ymd');
                Cache::add($phoneDayKey, 0, Date::now()->addDay());
                $currentPhoneDayCount = Cache::increment($phoneDayKey);

                if ($currentPhoneDayCount > $dayLimit) {
                    return [
                        'message' => 'আপনার দৈনিক অর্ডার সীমা শেষ হয়েছে। আপনার একটি অর্ডার আগেই গ্রহণ করা হয়েছে; আপনি আরও অর্ডার করতে চাইলে আমাদের হেল্পলাইন নম্বরে যোগাযোগ করুন।',
                        'alert-type' => 'danger',
                    ];
                }

                $todayPhoneOrders = Order::query()
                    ->where('phone', $request->phone)
                    ->whereDate('created_at', Date::today())
                    ->count();

                if ($todayPhoneOrders >= $dayLimit) {
                    return [
                        'message' => 'আপনার দৈনিক অর্ডার সীমা শেষ হয়েছে। আপনার একটি অর্ডার আগেই গ্রহণ করা হয়েছে; আপনি আরও অর্ডার করতে চাইলে আমাদের হেল্পলাইন নম্বরে যোগাযোগ করুন।',
                        'alert-type' => 'danger',
                    ];
                }
            }
        }

        // If no fingerprint exists, apply only phone checks and skip device checks.
        if ($fingerprint === null) {
            return null;
        }

        if ($hourLimit > 0) {
            $hourKey = 'order_rate:hour:fingerprint:'.$fingerprint.':'.Date::now()->format('YmdH');
            Cache::add($hourKey, 0, Date::now()->addHour());
            $currentHourCount = Cache::increment($hourKey);

            if ($currentHourCount > $hourLimit) {
                return [
                    'message' => 'এই ডিভাইস থেকে স্বল্প সময়ে অনেকগুলো অর্ডারের চেষ্টা করা হয়েছে। আপনার একটি অর্ডার আগেই গ্রহণ করা হয়েছে; আপনি আরও অর্ডার করতে চাইলে আমাদের হেল্পলাইন নম্বরে যোগাযোগ করুন।',
                    'alert-type' => 'danger',
                ];
            }
        }

        if ($dayLimit > 0) {
            $dayKey = 'order_rate:day:fingerprint:'.$fingerprint.':'.Date::now()->format('Ymd');
            Cache::add($dayKey, 0, Date::now()->addDay());
            $currentDayCount = Cache::increment($dayKey);

            if ($currentDayCount > $dayLimit) {
                return [
                    'message' => 'এই ডিভাইসের দৈনিক অর্ডার সীমা শেষ হয়েছে। আপনার একটি অর্ডার আগেই গ্রহণ করা হয়েছে; আপনি আরও অর্ডার করতে চাইলে আমাদের হেল্পলাইন নম্বরে যোগাযোগ করুন।',
                    'alert-type' => 'danger',
                ];
            }
        }

        $lockKey = 'order_lock:fingerprint:'.$fingerprint;
        if (! Cache::add($lockKey, true, Date::now()->addMinutes(2))) {
            return [
                'message' => 'গত ২ মিনিটের মধ্যে আপনার একটি অর্ডার গ্রহণ করা হয়েছে। আপনি আরও অর্ডার করতে চাইলে আমাদের হেল্পলাইন নম্বরে যোগাযোগ করুন।',
                'alert-type' => 'danger',
            ];
        }

        return null;
    }

    private function normalizePhone(string $phone): string
    {
        $normalized = preg_replace('/\D+/', '', trim($phone));

        return is_string($normalized) ? $normalized : '';
    }

    private function csvList(string $raw): array
    {
        if ($raw === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            explode(',', $raw),
        )));
    }

    private function resolveDeviceId(Request $request): ?string
    {
        $candidateKeys = ['device_id', 'deviceId', 'device-id'];

        foreach ($candidateKeys as $key) {
            $cookieValue = $request->cookie($key);
            if (is_string($cookieValue) && trim($cookieValue) !== '') {
                return trim($cookieValue);
            }

            $inputValue = $request->input($key);
            if (is_string($inputValue) && trim($inputValue) !== '') {
                return trim($inputValue);
            }
        }

        $headerValue = $request->header('X-Device-Id');
        if (is_string($headerValue) && trim($headerValue) !== '') {
            return trim($headerValue);
        }

        return null;
    }

    private function suspiciousOrderNotification(): array
    {
        return [
            'message' => 'আমাদের সিস্টেমে আপনার অর্ডারটি সন্ধেহজনক মনে হচ্ছে। কোন ফেইক অর্ডার শনাক্ত হলেই আপনার ব্যবহৃত এই ডিভাইস শনাক্ত করে আইনি পদক্ষেপ নেয়া হবে। ',
            'alert-type' => 'danger',
        ];
    }

    private function buildDeviceFingerprint(?string $deviceId, ?string $sessionId, string $ip, ?string $userAgent, Request $request): ?string
    {
        $signals = $this->collectFingerprintSignals($request, $deviceId, $sessionId, $ip, $userAgent);
        if ($signals === []) {
            return null;
        }

        return hash('sha256', json_encode($signals, JSON_UNESCAPED_SLASHES));
    }

    private function collectFingerprintSignals(Request $request, ?string $deviceId, ?string $sessionId, string $ip, ?string $userAgent): array
    {
        $header = static fn (string $name) => trim((string) $request->header($name, ''));
        $input = static fn (string $name) => trim((string) $request->input($name, ''));

        $signals = [
            'device_id' => is_string($deviceId) ? trim($deviceId) : '',
            'session_id' => is_string($sessionId) ? trim($sessionId) : '',
            'user_agent' => is_string($userAgent) ? trim($userAgent) : '',
            'accept_language' => $header('Accept-Language'),
            'sec_ch_ua' => $header('Sec-CH-UA'),
            'sec_ch_ua_mobile' => $header('Sec-CH-UA-Mobile'),
            'sec_ch_ua_platform' => $header('Sec-CH-UA-Platform'),
            'accept_encoding' => $header('Accept-Encoding'),
            'dnt' => $header('DNT'),
            'timezone' => $input('timezone') !== '' ? $input('timezone') : $header('X-Timezone'),
            'screen' => $input('screen') !== '' ? $input('screen') : $header('X-Screen-Res'),
            'platform' => $input('platform'),
            'cpu_class' => $input('cpu_class'),
            'touch_points' => $input('touch_points'),
            'webgl' => $input('webgl'),
            'canvas' => $input('canvas'),
            'plugins_hash' => $input('plugins_hash'),
            // Keep only subnet-level IP hint to reduce volatility for mobile networks.
            'ip_hint' => $this->extractIpHint($ip),
        ];

        ksort($signals);

        return array_filter($signals, static fn ($value) => $value !== '');
    }

    private function extractIpHint(string $ip): string
    {
        if ($ip === '') {
            return '';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            if (count($parts) === 4) {
                return $parts[0].'.'.$parts[1].'.'.$parts[2].'.0/24';
            }
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $segments = explode(':', $ip);

            return implode(':', array_slice($segments, 0, 4)).'::/64';
        }

        return '';
    }
}
