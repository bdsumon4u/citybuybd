<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Support\FacebookTracking;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

final class FacebookConversionsApiService
{
    public function trackViewContent(Product $product, ?Request $request = null, int $quantity = 1, ?string $eventId = null): void
    {
        $unitPrice = $this->resolveProductPrice($product);

        $this->sendEvent('ViewContent', $request, [
            'content_name' => $product->name,
            'content_type' => 'product',
            'content_ids' => [(string) $product->id],
            'contents' => [[
                'id' => (string) $product->id,
                'quantity' => max($quantity, 1),
                'item_price' => $unitPrice,
            ]],
            'currency' => $this->resolveCurrencyCode(),
            'value' => $unitPrice * max($quantity, 1),
            'num_items' => max($quantity, 1),
        ], [], $eventId);
    }

    public function trackAddToCart(?Product $product, int $quantity = 1, ?float $unitPrice = null, ?Request $request = null, ?string $eventId = null): void
    {
        if (! $product) {
            return;
        }

        $price = $unitPrice ?? $this->resolveProductPrice($product);

        $this->sendEvent('AddToCart', $request, [
            'content_name' => $product->name,
            'content_type' => 'product',
            'content_ids' => [(string) $product->id],
            'contents' => [[
                'id' => (string) $product->id,
                'quantity' => max($quantity, 1),
                'item_price' => $price,
            ]],
            'currency' => $this->resolveCurrencyCode(),
            'value' => $price * max($quantity, 1),
            'num_items' => max($quantity, 1),
        ], [], $eventId);
    }

    public function trackInitiateCheckout(iterable $items, float $value, ?Request $request = null, ?string $eventId = null): void
    {
        $normalizedItems = $this->normalizeItems($items);

        if ($normalizedItems === []) {
            return;
        }

        $this->sendEvent('InitiateCheckout', $request, [
            'content_type' => 'product',
            'content_ids' => array_values(array_filter(array_map(static fn (array $item): string => $item['id'], $normalizedItems))),
            'contents' => $normalizedItems,
            'currency' => $this->resolveCurrencyCode(),
            'value' => $value,
            'num_items' => array_sum(array_map(static fn (array $item): int => $item['quantity'], $normalizedItems)),
        ], [], $eventId);
    }

    public function trackPurchase(Order $order, ?Request $request = null, ?string $eventId = null): void
    {
        $order->loadMissing(['items.product']);

        $items = $this->normalizeItems($order->items ?? []);

        if ($items === []) {
            return;
        }

        $resolvedEventId = $eventId ?: ('order_' . $order->id);

        $this->sendEvent('Purchase', $request, [
            'content_type' => 'product',
            'content_ids' => array_values(array_filter(array_map(static fn (array $item): string => $item['id'], $items))),
            'contents' => $items,
            'currency' => $this->resolveCurrencyCode(),
            'value' => (float) ($order->total ?? $order->sub_total ?? 0),
            'num_items' => (int) ($order->ordered_quantity ?? array_sum(array_map(static fn (array $item): int => $item['quantity'], $items))),
            'order_id' => (string) $order->id,
        ], $this->buildUserDataFromOrder($order, $request), $resolvedEventId);
    }

    public function trackPageView(?Request $request = null, ?string $eventId = null): void
    {
        $this->sendEvent('PageView', $request, [], [], $eventId);
    }

    private function sendEvent(string $eventName, ?Request $request, array $customData, array $extraUserData = [], ?string $eventId = null): void
    {
        $settings = Settings::query()->first();

        if (! $settings || empty($settings->fb_pixel_id) || empty($settings->fb_access_token)) {
            return;
        }

        $resolvedEventId = ($eventId !== null && trim($eventId) !== '') ? trim($eventId) : (string) Str::uuid();
        $endpoint = 'https://graph.facebook.com/v22.0/'.urlencode((string) $settings->fb_pixel_id).'/events';
        $payload = [
            'data' => [[
                'event_name' => $eventName,
                'event_time' => now()->timestamp,
                'event_id' => $resolvedEventId,
                'action_source' => 'website',
                'event_source_url' => $this->resolveEventSourceUrl($request),
                'user_data' => $this->buildUserData($request, $extraUserData),
                'custom_data' => $customData,
            ]],
        ];

        $testEventCode = trim((string) ($settings->fb_test_event_code ?? ''));
        if ($testEventCode !== '') {
            $payload['test_event_code'] = $testEventCode;
        }

        try {
            /** @var Response $response */
            $response = Http::timeout(10)
                ->retry(2, 250)
                ->acceptJson()
                ->asJson()
                ->post($endpoint.'?access_token='.urlencode((string) $settings->fb_access_token), $payload);

            if (! $response->successful()) {
                Log::warning('Facebook CAPI request failed', [
                    'event' => $eventName,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (Throwable $throwable) {
            Log::warning('Facebook CAPI request exception', [
                'event' => $eventName,
                'message' => $throwable->getMessage(),
            ]);
        }
    }

    private function buildUserData(?Request $request, array $extraUserData = []): array
    {
        $fbp = FacebookTracking::resolveFbp($request);
        $fbc = FacebookTracking::resolveFbc($request);

        $userData = [
            'client_ip_address' => $request?->ip() ?? (function_exists('request') ? request()->ip() : null),
            'client_user_agent' => $request?->userAgent() ?? (function_exists('request') ? request()->userAgent() : null),
            'fbp' => $fbp,
            'fbc' => $fbc,
            'em' => $this->hashEmail($request?->input('email') ?: $request?->input('cus_email') ?: $request?->input('buyer_email')),
            'ph' => $this->hashPhone($request?->input('phone') ?: $request?->input('cus_phone') ?: $request?->input('buyer_phone')),
            'country' => $this->hashCountry('bd'),
        ];

        return array_filter(
            array_merge($userData, $extraUserData),
            static fn ($value): bool => $value !== null && $value !== ''
        );
    }

    private function buildUserDataFromOrder(Order $order, ?Request $request = null): array
    {
        $fbp = $order->fbp ?: FacebookTracking::resolveFbp($request);
        $fbc = $order->fbc ?: FacebookTracking::resolveFbc($request);
        $names = $this->splitName($order->name);

        return array_filter([
            'client_ip_address' => $request?->ip() ?? $order->ip_address ?? (function_exists('request') ? request()->ip() : null),
            'client_user_agent' => $request?->userAgent() ?? (function_exists('request') ? request()->userAgent() : null),
            'fbp' => $fbp,
            'fbc' => $fbc,
            'em' => $this->hashEmail($order->email ?: $request?->input('email')),
            'ph' => $this->hashPhone($order->phone ?: $request?->input('phone')),
            'fn' => $names['fn'] ? $this->hashString($names['fn']) : null,
            'ln' => $names['ln'] ? $this->hashString($names['ln']) : null,
            'ct' => $this->hashCity($order->city),
            'country' => $this->hashCountry('bd'),
            'external_id' => (string) $order->id,
        ], static fn ($value): bool => $value !== null && $value !== '');
    }

    private function normalizeItems(iterable $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            $productId = $this->extractValue($item, ['product_id', 'id']);
            $quantity = (int) ($this->extractValue($item, ['quantity', 'qty']) ?? 1);
            $price = (float) ($this->extractValue($item, ['price', 'item_price']) ?? 0);
            $product = $this->extractProduct($item);

            if (! $productId && $product) {
                $productId = $product->id;
            }

            if (! $price && $product) {
                $price = $this->resolveProductPrice($product);
            }

            if (! $productId) {
                continue;
            }

            $normalized[] = [
                'id' => (string) $productId,
                'quantity' => max($quantity, 1),
                'item_price' => $price,
            ];
        }

        return $normalized;
    }

    private function extractProduct(mixed $item): ?Product
    {
        if ($item instanceof Cart) {
            return $item->relationLoaded('product') ? $item->product : $item->product()->first();
        }

        if ($item instanceof Product) {
            return $item;
        }

        if (is_object($item) && property_exists($item, 'product') && $item->product instanceof Product) {
            return $item->product;
        }

        return null;
    }

    private function extractValue(mixed $item, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (is_array($item) && array_key_exists($key, $item)) {
                return $item[$key];
            }

            if (is_object($item) && isset($item->{$key})) {
                return $item->{$key};
            }
        }

        return null;
    }

    private function resolveProductPrice(Product $product): float
    {
        return (float) ($product->offer_price ?? $product->regular_price ?? 0);
    }

    private function resolveCurrencyCode(): string
    {
        $settings = Settings::query()->first();
        $configuredCurrency = strtoupper(trim((string) ($settings?->currency ?? '')));

        if (preg_match('/^[A-Z]{3}$/', $configuredCurrency) === 1) {
            return $configuredCurrency;
        }

        return 'BDT';
    }

    private function resolveEventSourceUrl(?Request $request): string
    {
        return $request?->fullUrl() ?? url()->current();
    }

    private function hashEmail(?string $email): ?string
    {
        if (! is_string($email)) {
            return null;
        }

        $normalized = trim(strtolower($email));
        if ($normalized === '' || ! str_contains($normalized, '@')) {
            return null;
        }

        return hash('sha256', $normalized);
    }

    private function hashPhone(?string $phone): ?string
    {
        if (! is_string($phone)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return null;
        }

        // Meta requires international country code prefix without + or leading zeros.
        // For Bangladesh numbers (11 digits starting with 01), prepend 88 -> 8801...
        if (str_starts_with($digits, '01') && strlen($digits) === 11) {
            $digits = '88' . $digits;
        } elseif (str_starts_with($digits, '0') && strlen($digits) === 11) {
            $digits = '88' . $digits;
        } elseif (str_starts_with($digits, '1') && strlen($digits) === 10) {
            $digits = '880' . $digits;
        }

        return hash('sha256', $digits);
    }

    private function hashCity(?string $city): ?string
    {
        if (! is_string($city)) {
            return null;
        }

        $normalized = preg_replace('/[^a-z0-9]/', '', strtolower(trim($city))) ?? '';
        if ($normalized === '') {
            return null;
        }

        return hash('sha256', $normalized);
    }

    private function hashCountry(string $countryCode = 'bd'): string
    {
        return hash('sha256', strtolower(trim($countryCode)));
    }

    private function splitName(?string $fullName): array
    {
        if (! is_string($fullName)) {
            return ['fn' => null, 'ln' => null];
        }

        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        if ($parts === []) {
            return ['fn' => null, 'ln' => null];
        }

        $fn = array_shift($parts);
        $ln = ! empty($parts) ? implode(' ', $parts) : null;

        return ['fn' => $fn, 'ln' => $ln];
    }

    private function hashString(?string $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $normalized = trim(strtolower($value));
        if ($normalized === '') {
            return null;
        }

        return hash('sha256', $normalized);
    }
}
