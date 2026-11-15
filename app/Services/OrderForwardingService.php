<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Settings;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderForwardingService
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    /**
     * Forward a newly created order from slave to master.
     */
    public function forwardOrder(Order $order): void
    {
        $settings = $this->getSettings();

        $isSlave = $this->isSlave($settings);

        Log::info('Forwarding check start', [
            'order_id' => $order->id,
            'role' => $isSlave ? 'slave' : 'master',
            'forwarding_enabled' => (bool) $settings->forwarding_enabled,
        ]);

        if (! $this->canForwardFromSlave($settings)) {
            Log::info('Forwarding skipped (config not eligible)', ['order_id' => $order->id]);

            return;
        }

        if ($order->slave_id !== null || $order->slave_domain !== null) {
            // Already a master-side copy; do not forward again.
            Log::info('Forwarding skipped (already master copy)', ['order_id' => $order->id]);

            return;
        }

        $payload = $this->buildOrderPayload($order);

        $targetUrl = rtrim((string) $settings->forwarding_master_domain, '/').'/api/forwarding/orders';

        Log::info('Forwarding request dispatch', [
            'order_id' => $order->id,
            'target' => $targetUrl,
        ]);

        try {
            $response = $this->postJson(
                $targetUrl,
                $payload,
                (string) $settings->forwarding_master_secret,
            );

            if ($this->isSuccess($response)) {
                $masterId = (int) ($response->json('master_id') ?? 0);

                $order->master_id = $masterId > 0 ? $masterId : null;
                $order->forwarding_status = self::STATUS_SUCCESS;
                $order->forwarding_error = null;
                $order->saveQuietly();

                Log::info('Forwarding success', [
                    'order_id' => $order->id,
                    'master_id' => $order->master_id,
                ]);
            } else {
                $order->forwarding_status = self::STATUS_FAILED;
                $order->forwarding_error = $this->responseError($response);
                $order->saveQuietly();

                Log::warning('Forwarding failed', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'error' => $order->forwarding_error,
                ]);
            }
        } catch (\Throwable $e) {
            $order->forwarding_status = self::STATUS_FAILED;
            $order->forwarding_error = 'Connection error: '.$e->getMessage();
            $order->saveQuietly();

            Log::error('Forwarding exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Retry forwarding manually.
     */
    public function retryForward(Order $order): void
    {
        Log::info('Forwarding retry requested', [
            'order_id' => $order->id,
            'current_status' => $order->forwarding_status,
        ]);

        if ($order->forwarding_status === self::STATUS_SUCCESS && ! empty($order->master_id)) {
            Log::info('Forwarding retry skipped (already succeeded)', ['order_id' => $order->id]);

            return;
        }

        // Reset status to pending then attempt forward.
        $order->forwarding_status = self::STATUS_PENDING;
        $order->forwarding_error = null;
        $order->saveQuietly();

        $this->forwardOrder($order);
    }

    /**
     * Send status from slave to master after local change.
     */
    public function pushStatusToMaster(Order $order): void
    {
        $settings = $this->getSettings();

        if (! $this->canForwardFromSlave($settings)) {
            Log::info('Push status to master skipped (config not eligible)', ['order_id' => $order->id]);

            return;
        }

        if ($order->master_id === null) {
            Log::info('Push status to master skipped (no master_id)', ['order_id' => $order->id]);

            return;
        }

        $statusName = $this->statusName($order);

        if ($statusName === null) {
            Log::warning('Push status to master skipped (unknown status)', ['order_id' => $order->id]);

            return;
        }

        $endpoint = $this->ensureScheme((string) $settings->forwarding_master_domain).'/api/forwarding/status';

        Log::info('Push status to master', [
            'order_id' => $order->id,
            'master_id' => $order->master_id,
            'status' => $statusName,
            'endpoint' => $endpoint,
        ]);

        try {
            $this->postJson($endpoint, [
                'master_id' => $order->master_id,
                'slave_order_id' => $order->id,
                'status' => $statusName,
            ], (string) $settings->forwarding_master_secret);
        } catch (\Throwable $e) {
            Log::error('Push status to master failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send status from master to slave after master change.
     */
    public function pushStatusToSlave(Order $order): void
    {
        $settings = $this->getSettings();

        if ($this->isSlave($settings) || empty($settings->forwarding_master_secret)) {
            Log::info('Push status to slave skipped (not master or no secret)', ['order_id' => $order->id]);

            return;
        }

        if ($order->slave_id === null || empty($order->slave_domain)) {
            Log::info('Push status to slave skipped (missing slave info)', ['order_id' => $order->id]);

            return;
        }

        $statusName = $this->statusName($order);

        if ($statusName === null) {
            Log::warning('Push status to slave skipped (unknown status)', ['order_id' => $order->id]);

            return;
        }

        $endpoint = $this->ensureScheme((string) $order->slave_domain).'/api/forwarding/status';

        Log::info('Push status to slave', [
            'order_id' => $order->id,
            'slave_id' => $order->slave_id,
            'status' => $statusName,
            'endpoint' => $endpoint,
        ]);

        try {
            $this->postJson($endpoint, [
                'master_id' => $order->id,
                'slave_order_id' => $order->slave_id,
                'status' => $statusName,
            ], (string) $settings->forwarding_master_secret);
        } catch (\Throwable $e) {
            Log::error('Push status to slave failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Validate master secret against stored value.
     */
    public function isValidSecret(?string $provided): bool
    {
        $settings = $this->getSettings();
        $expected = (string) $settings->forwarding_master_secret;

        if ($expected === '') {
            Log::warning('Secret validation failed (empty expected)');

            return false;
        }

        $valid = hash_equals($expected, (string) $provided);

        Log::info('Secret validation result', ['valid' => $valid]);

        return $valid;
    }

    /**
     * Map status string to existing numeric code.
     */
    public function statusCodeFromString(?string $status): ?int
    {
        if ($status === null) {
            return null;
        }

        return Order::statusCodeFromName($status);
    }

    private function getSettings(): Settings
    {
        return Settings::getSettings();
    }

    private function canForwardFromSlave(Settings $settings): bool
    {
        return $this->isSlave($settings)
            && $settings->forwarding_enabled
            && ! empty($settings->forwarding_master_domain)
            && ! empty($settings->forwarding_master_secret);
    }

    private function buildOrderPayload(Order $order): array
    {
        $statusName = $this->statusName($order);

        $order->loadMissing('many_cart.product');
        if ($order->many_cart->isEmpty()) {
            $order->load('many_cart.product');
        }

        $items = [];
        foreach ($order->many_cart as $cart) {
            $product = $cart->product;
            $items[] = [
                'product_id' => $cart->product_id,
                'product_name' => $product?->name ?? 'Product #'.$cart->product_id,
                'slug' => $product?->slug ?? null,
                'sku' => $product?->sku ?? null,
                'package' => $cart->package ?? null,
                'attribute' => $cart->attribute ?? null,
                'quantity' => (int) $cart->quantity,
                'price' => (int) ($cart->price ?? $product?->offer_price ?? 0),
            ];
        }

        return [
            'slave_order_id' => $order->id,
            'slave_domain' => $this->getSlaveDomain(),
            'customer' => [
                'name' => $order->name,
                'phone' => $order->phone,
                'email' => $order->email,
                'address' => $order->address,
                'city' => $order->city,
                'zone' => $order->zone,
            ],
            'shipping_cost' => $order->shipping_cost,
            'discount' => $order->discount,
            'sub_total' => $order->sub_total,
            'total' => $order->total,
            'order_type' => $order->order_type,
            'order_note' => $order->order_note,
            'status' => $statusName,
            'items' => $items,
            'coming' => $order->coming,
            'attribution' => [
                'utm_source' => $order->utm_source,
                'utm_medium' => $order->utm_medium,
                'utm_campaign' => $order->utm_campaign,
                'fbp' => $order->fbp,
                'fbc' => $order->fbc,
            ],
        ];
    }

    private function getSlaveDomain(): string
    {
        $host = '';
        if (request()->hasHeader('Host')) {
            $host = request()->getHost();
        }

        if (empty($host) || in_array($host, ['localhost', '127.0.0.1'], true)) {
            $appUrl = config('app.url');
            if (! empty($appUrl)) {
                $host = parse_url($appUrl, PHP_URL_HOST) ?? '';
            }
        }

        $host = strtolower(trim((string) $host));
        $host = preg_replace('/^www\./i', '', $host);
        $host = preg_replace('/:\d+$/', '', $host);

        return $host ?: 'slave';
    }

    private function statusName(Order $order): ?string
    {
        return Order::STATUS_MAP[$order->status] ?? null;
    }

    private function postJson(string $url, array $payload, string $secret): Response
    {
        Log::info('HTTP POST', [
            'url' => $url,
            'payload' => $payload,
        ]);

        $http = Http::acceptJson()
            ->timeout(45)
            ->connectTimeout(15)
            ->withHeaders([
                'X-Master-Secret' => $secret,
            ])
            ->withOptions([
                'allow_redirects' => [
                    'max' => 5,
                    'strict' => true, // Preserve POST method on redirects
                    'referer' => true,
                    'track_redirects' => true,
                ],
            ]);

        // Disable SSL verification for local development environments
        if (app()->environment('local') || str_contains($url, '.test') || str_contains($url, 'localhost')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($url, $payload);

        Log::info('HTTP POST response', [
            'url' => $url,
            'status' => $response->status(),
            'successful' => $response->successful(),
        ]);

        return $response;
    }

    private function isSuccess(Response $response): bool
    {
        return $response->successful() && (bool) $response->json('success', false);
    }

    private function responseError(Response $response): string
    {
        if ($response->successful()) {
            return '';
        }

        $body = $response->json();
        if (is_array($body)) {
            $message = Arr::get($body, 'message') ?? Arr::get($body, 'error');
            if ($message) {
                return (string) $message;
            }
        }

        return $response->body();
    }

    private function ensureScheme(string $url): string
    {
        if (preg_match('~^https?://~i', $url)) {
            return rtrim($url, '/');
        }

        // Ensure scheme is present; default to http
        return 'http://'.rtrim($url, '/');
    }

    private function isSlave(Settings $settings): bool
    {
        return ! empty(trim((string) $settings->forwarding_master_domain));
    }
}
