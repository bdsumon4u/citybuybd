<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

class OrderDefenderService
{
    public function defend(Request $request, Settings $settings): ?array
    {
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
        );

        // If no identifier exists, skip rate limiting instead of blocking legitimate users.
        if ($fingerprint === null) {
            return null;
        }

        $hourLimit = (int) ($settings->orders_per_hour_limit ?? 0);
        if ($hourLimit > 0) {
            $hourKey = 'order_rate:hour:fingerprint:'.$fingerprint.':'.Date::now()->format('YmdH');
            Cache::add($hourKey, 0, Date::now()->addHour());
            $currentHourCount = Cache::increment($hourKey);

            if ($currentHourCount > $hourLimit) {
                return [
                    'message' => 'Too many orders detected recently from this device. Please try again later.',
                    'alert-type' => 'danger',
                ];
            }
        }

        $dayLimit = (int) ($settings->orders_per_day_limit ?? 0);
        if ($dayLimit > 0) {
            $dayKey = 'order_rate:day:fingerprint:'.$fingerprint.':'.Date::now()->format('Ymd');
            Cache::add($dayKey, 0, Date::now()->addDay());
            $currentDayCount = Cache::increment($dayKey);

            if ($currentDayCount > $dayLimit) {
                return [
                    'message' => 'You have reached the daily order limit from this device. Please try again tomorrow.',
                    'alert-type' => 'danger',
                ];
            }
        }

        $lockKey = 'order_lock:fingerprint:'.$fingerprint;
        if (! Cache::add($lockKey, true, Date::now()->addMinutes(2))) {
            return [
                'message' => 'You have already placed an order in the last 2 minutes. Please try again later.',
                'alert-type' => 'danger',
            ];
        }

        return null;
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

    private function buildDeviceFingerprint(?string $deviceId, ?string $sessionId, string $ip, ?string $userAgent): ?string
    {
        if (is_string($deviceId) && $deviceId !== '') {
            return sha1('device:'.$deviceId);
        }

        if (is_string($sessionId) && $sessionId !== '') {
            return sha1('session:'.$sessionId);
        }

        // if (is_string($userAgent) && $userAgent !== '') {
        //     return sha1('ipua:'.$ip.'|'.$userAgent);
        // }

        // if ($ip !== '') {
        //     return sha1('ip:'.$ip);
        // }

        return null;
    }
}