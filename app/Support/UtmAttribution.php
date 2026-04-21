<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;

class UtmAttribution
{
    public const COOKIE_NAME = 'utm_attribution';

    public const COOKIE_MINUTES = 60 * 24 * 90;

    public const KEYS = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];

    public static function fromRequest(Request $request): array
    {
        $values = self::fromCookie($request->cookie(self::COOKIE_NAME));

        foreach (self::KEYS as $key) {
            $value = self::normalize($request->query($key));
            if ($value !== null) {
                $values[$key] = $value;
            }
        }

        return $values;
    }

    public static function fromCookie(mixed $cookieValue): array
    {
        if (! is_string($cookieValue) || trim($cookieValue) === '') {
            return [];
        }

        $decoded = json_decode($cookieValue, true);
        if (! is_array($decoded)) {
            return [];
        }

        $normalized = [];
        foreach (self::KEYS as $key) {
            $value = self::normalize($decoded[$key] ?? null);
            if ($value !== null) {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    public static function normalize(mixed $value): ?string
    {
        if (! is_scalar($value)) {
            return null;
        }

        $string = trim((string) $value);
        if ($string === '') {
            return null;
        }

        return mb_substr($string, 0, 255);
    }
}
