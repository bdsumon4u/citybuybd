<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class FacebookTracking
{
    public const COOKIE_FBP = '_fbp';

    public const COOKIE_FBC = '_fbc';

    public const SESSION_FBP = '_fbp';

    public const SESSION_FBC = '_fbc';

    public const SESSION_FBCLID = 'fbclid';

    public const COOKIE_MINUTES = 60 * 24 * 90; // 90 days

    /**
     * Resolve _fbp value from cookies, session, or generate a valid standard identifier.
     */
    public static function resolveFbp(?Request $request = null): string
    {
        $request = $request ?? (function_exists('request') ? request() : null);

        // 1. Check Laravel decrypted cookie bag (exempted in EncryptCookies)
        $fbp = self::sanitize($request?->cookie(self::COOKIE_FBP));

        // 2. Check raw request cookies
        if ($fbp === null) {
            $fbp = self::sanitize($request?->cookies->get(self::COOKIE_FBP));
        }

        // 3. Check PHP superglobal $_COOKIE
        if ($fbp === null && isset($_COOKIE[self::COOKIE_FBP])) {
            $fbp = self::sanitize($_COOKIE[self::COOKIE_FBP]);
        }

        // 4. Check user session
        if ($fbp === null && self::hasSession() && session()->has(self::SESSION_FBP)) {
            $fbp = self::sanitize(session(self::SESSION_FBP));
        }

        // Ensure valid Meta format: fb.1.<creation_timestamp_ms>.<random>
        if ($fbp === null || ! str_starts_with($fbp, 'fb.')) {
            $fbp = self::generateFbp();
        }

        // Persist to session
        if (self::hasSession()) {
            session([self::SESSION_FBP => $fbp]);
        }

        return $fbp;
    }

    /**
     * Resolve _fbc value from URL query parameter, cookies, or session.
     */
    public static function resolveFbc(?Request $request = null): ?string
    {
        $request = $request ?? (function_exists('request') ? request() : null);

        // 1. Fresh ad click via query parameter (takes precedence)
        $queryFbclid = self::sanitize($request?->query('fbclid') ?: $request?->input('fbclid'));
        if ($queryFbclid !== null) {
            $fbc = self::buildFbc($queryFbclid);
            if (self::hasSession()) {
                session([
                    self::SESSION_FBC => $fbc,
                    self::SESSION_FBCLID => $queryFbclid,
                ]);
            }

            return $fbc;
        }

        // 2. Check Laravel decrypted cookie bag
        $fbc = self::sanitize($request?->cookie(self::COOKIE_FBC));

        // 3. Check raw request cookies
        if ($fbc === null) {
            $fbc = self::sanitize($request?->cookies->get(self::COOKIE_FBC));
        }

        // 4. Check PHP superglobal $_COOKIE
        if ($fbc === null && isset($_COOKIE[self::COOKIE_FBC])) {
            $fbc = self::sanitize($_COOKIE[self::COOKIE_FBC]);
        }

        // 5. Check session for stored _fbc
        if ($fbc === null && self::hasSession() && session()->has(self::SESSION_FBC)) {
            $fbc = self::sanitize(session(self::SESSION_FBC));
        }

        // 6. Check session for stored fbclid to reconstruct _fbc
        if ($fbc === null && self::hasSession() && session()->has(self::SESSION_FBCLID)) {
            $storedFbclid = self::sanitize(session(self::SESSION_FBCLID));
            if ($storedFbclid !== null) {
                $fbc = self::buildFbc($storedFbclid);
            }
        }

        if ($fbc !== null && str_starts_with($fbc, 'fb.')) {
            if (self::hasSession()) {
                session([self::SESSION_FBC => $fbc]);
            }

            return $fbc;
        }

        return null;
    }

    /**
     * Generate standard Meta Browser ID (_fbp).
     * Format: fb.1.<creation_timestamp_ms>.<random_10_digit_int>
     */
    public static function generateFbp(): string
    {
        $timeMs = (int) round(microtime(true) * 1000);
        $random = random_int(1000000000, 9999999999);

        return "fb.1.{$timeMs}.{$random}";
    }

    /**
     * Build standard Meta Click ID (_fbc) from a fbclid string.
     * Format: fb.1.<creation_timestamp_ms>.<fbclid>
     */
    public static function buildFbc(string $fbclid): string
    {
        $timeMs = (int) round(microtime(true) * 1000);

        return "fb.1.{$timeMs}.{$fbclid}";
    }

    /**
     * Queue first-party unencrypted cookies to the response.
     */
    public static function queueCookies(?Request $request = null): void
    {
        $request = $request ?? (function_exists('request') ? request() : null);
        $isSecure = $request?->isSecure() ?? false;

        $fbp = self::resolveFbp($request);
        Cookie::queue(Cookie::make(
            self::COOKIE_FBP,
            $fbp,
            self::COOKIE_MINUTES,
            '/',
            null,
            $isSecure,
            false, // httpOnly = false so browser JS / pixel can read
            false,
            'Lax'
        ));

        $fbc = self::resolveFbc($request);
        if ($fbc !== null) {
            Cookie::queue(Cookie::make(
                self::COOKIE_FBC,
                $fbc,
                self::COOKIE_MINUTES,
                '/',
                null,
                $isSecure,
                false, // httpOnly = false so browser JS / pixel can read
                false,
                'Lax'
            ));
        }
    }

    private static function hasSession(): bool
    {
        return function_exists('session') && session()->isStarted();
    }

    private static function sanitize(mixed $value): ?string
    {
        if (! is_scalar($value)) {
            return null;
        }

        $trimmed = trim((string) $value, " \t\n\r\0\x0B\"'");

        return $trimmed !== '' ? $trimmed : null;
    }
}
