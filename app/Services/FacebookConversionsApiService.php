<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

final class FacebookConversionsApiService
{
    public function trackViewContent(Product $product, ?Request $request = null, int $quantity = 1): void
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
        ]);
    }

    public function trackAddToCart(?Product $product, int $quantity = 1, ?float $unitPrice = null, ?Request $request = null): void
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
        ]);
    }

    public function trackInitiateCheckout(iterable $items, float $value, ?Request $request = null): void
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
        ]);
    }

    public function trackPurchase(Order $order, ?Request $request = null): void
    {
        $order->loadMissing(['items.product']);

        $items = $this->normalizeItems($order->items ?? []);

        if ($items === []) {
            return;
        }

        $this->sendEvent('Purchase', $request, [
            'content_type' => 'product',
            'content_ids' => array_values(array_filter(array_map(static fn (array $item): string => $item['id'], $items))),
            'contents' => $items,
            'currency' => $this->resolveCurrencyCode(),
            'value' => (float) ($order->total ?? $order->sub_total ?? 0),
            'num_items' => (int) ($order->ordered_quantity ?? array_sum(array_map(static fn (array $item): int => $item['quantity'], $items))),
            'order_id' => (string) $order->id,
        ], $this->buildUserDataFromOrder($order, $request));
    }

    public function trackPageView(?Request $request = null): void
    {
        $this->sendEvent('PageView', $request, []);
    }

    private function sendEvent(string $eventName, ?Request $request, array $customData, array $extraUserData = []): void
    {
        $settings = Settings::query()->first();

        if (! $settings || empty($settings->fb_pixel_id) || empty($settings->fb_access_token)) {
            return;
        }

        $eventId = (string) Str::uuid();
        $endpoint = 'https://graph.facebook.com/v22.0/'.urlencode((string) $settings->fb_pixel_id).'/events';
        $payload = [
            'data' => [[
                'event_name' => $eventName,
                'event_time' => now()->timestamp,
                'event_id' => $eventId,
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
        return array_filter(array_merge([
            'client_ip_address' => $request?->ip() ?? request()->ip(),
            'client_user_agent' => $request?->userAgent() ?? request()->userAgent(),
            'fbp' => $this->cookieValue($request, '_fbp'),
            'fbc' => $this->cookieValue($request, '_fbc'),
            'em' => $this->hashValue($request?->input('email') ?: $request?->input('cus_email') ?: $request?->input('buyer_email')),
            'ph' => $this->hashValue($request?->input('phone') ?: $request?->input('cus_phone') ?: $request?->input('buyer_phone')),
        ], $extraUserData), static fn ($value): bool => $value !== null && $value !== '');
    }

    private function buildUserDataFromOrder(Order $order, ?Request $request = null): array
    {
        return array_filter([
            'client_ip_address' => $request?->ip() ?? $order->ip_address ?? request()->ip(),
            'client_user_agent' => $request?->userAgent() ?? request()->userAgent(),
            'fbp' => $this->cookieValue($request, '_fbp'),
            'fbc' => $this->cookieValue($request, '_fbc'),
            'em' => $this->hashValue($order->email),
            'ph' => $this->hashValue($order->phone),
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

    private function cookieValue(?Request $request, string $name): ?string
    {
        $value = $request?->cookie($name);

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return trim($value);
    }

    private function hashValue(?string $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $normalized = trim(strtolower($value));
        if ($normalized === '') {
            return null;
        }

        if (str_contains($normalized, '@')) {
            return hash('sha256', $normalized);
        }

        $normalized = preg_replace('/\D+/', '', $normalized) ?? '';
        if ($normalized === '') {
            return null;
        }

        return hash('sha256', $normalized);
    }
}