<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\IncompleteOrder;
use App\Models\Settings;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IncompleteOrderForwardingService
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    public function forwardIncomplete(IncompleteOrder $incompleteOrder): void
    {
        $settings = $this->getSettings();

        if (! $this->canForwardFromSlave($settings)) {
            Log::info('Incomplete forwarding skipped (config not eligible)', ['incomplete_order_id' => $incompleteOrder->id]);

            return;
        }

        if ($incompleteOrder->slave_id !== null && $incompleteOrder->slave_domain !== null) {
            Log::info('Incomplete forwarding skipped (already master copy)', ['incomplete_order_id' => $incompleteOrder->id]);

            return;
        }

        $payload = $this->buildPayload($incompleteOrder);
        $endpoint = rtrim((string) $settings->forwarding_master_domain, '/').'/api/forwarding/incomplete-orders';

        Log::info('Incomplete forwarding request dispatch', [
            'incomplete_order_id' => $incompleteOrder->id,
            'target' => $endpoint,
        ]);

        $response = $this->postJson($endpoint, $payload, (string) $settings->forwarding_master_secret);

        if ($this->isSuccess($response)) {
            $masterId = (int) ($response->json('master_id') ?? 0);

            $incompleteOrder->master_id = $masterId > 0 ? $masterId : null;
            $incompleteOrder->forwarding_status = self::STATUS_SUCCESS;
            $incompleteOrder->forwarding_error = null;
            $incompleteOrder->save();

            Log::info('Incomplete forwarding success', [
                'incomplete_order_id' => $incompleteOrder->id,
                'master_id' => $incompleteOrder->master_id,
            ]);
        } else {
            $incompleteOrder->forwarding_status = self::STATUS_FAILED;
            $incompleteOrder->forwarding_error = $this->responseError($response);
            $incompleteOrder->save();

            Log::warning('Incomplete forwarding failed', [
                'incomplete_order_id' => $incompleteOrder->id,
                'status' => $response->status(),
                'error' => $incompleteOrder->forwarding_error,
            ]);
        }
    }

    public function pushCancelToPeer(IncompleteOrder $incompleteOrder): void
    {
        $this->pushActionToPeer($incompleteOrder, 'cancel');
    }

    public function pushDeleteToPeer(IncompleteOrder $incompleteOrder): void
    {
        $this->pushActionToPeer($incompleteOrder, 'delete');
    }

    public function isValidSecret(?string $provided): bool
    {
        $settings = $this->getSettings();
        $expected = (string) $settings->forwarding_master_secret;

        if ($expected === '') {
            return false;
        }

        return hash_equals($expected, (string) $provided);
    }

    private function pushActionToPeer(IncompleteOrder $incompleteOrder, string $action): void
    {
        $settings = $this->getSettings();
        $isSlave = $this->isSlave($settings);

        if ($isSlave) {
            if ($incompleteOrder->master_id === null) {
                Log::info('Incomplete peer action skipped (missing master_id)', ['incomplete_order_id' => $incompleteOrder->id]);

                return;
            }

            $endpoint = $this->ensureScheme((string) $settings->forwarding_master_domain).'/api/forwarding/incomplete-actions';

            $this->postJson($endpoint, [
                'action' => $action,
                'master_id' => $incompleteOrder->master_id,
                'slave_order_id' => $incompleteOrder->id,
                'cancellation_reason' => $incompleteOrder->cancellation_reason,
            ], (string) $settings->forwarding_master_secret);

            return;
        }

        if ($incompleteOrder->slave_id === null || empty($incompleteOrder->slave_domain)) {
            Log::info('Incomplete peer action skipped (missing slave source)', ['incomplete_order_id' => $incompleteOrder->id]);

            return;
        }

        $endpoint = $this->ensureScheme((string) $incompleteOrder->slave_domain).'/api/forwarding/incomplete-actions';

        $this->postJson($endpoint, [
            'action' => $action,
            'master_id' => $incompleteOrder->id,
            'slave_order_id' => $incompleteOrder->slave_id,
            'cancellation_reason' => $incompleteOrder->cancellation_reason,
        ], (string) $settings->forwarding_master_secret);
    }

    private function buildPayload(IncompleteOrder $incompleteOrder): array
    {
        return [
            'slave_order_id' => $incompleteOrder->id,
            'slave_domain' => $incompleteOrder->slave_domain ?: request()->getHost(),
            'token' => $incompleteOrder->token,
            'user_id' => $incompleteOrder->user_id,
            'ip_address' => $incompleteOrder->ip_address,
            'name' => $incompleteOrder->name,
            'address' => $incompleteOrder->address,
            'phone' => $incompleteOrder->phone,
            'shipping_method_label' => $incompleteOrder->shipping_method_label,
            'shipping_amount' => $incompleteOrder->shipping_amount,
            'sub_total' => $incompleteOrder->sub_total,
            'total' => $incompleteOrder->total,
            'product_id' => $incompleteOrder->product_id,
            'product_slug' => $incompleteOrder->product_slug,
            'cart_snapshot' => $incompleteOrder->cart_snapshot,
            'status' => $incompleteOrder->status,
            'completed_at' => optional($incompleteOrder->completed_at)?->toDateTimeString(),
            'cancellation_reason' => $incompleteOrder->cancellation_reason,
            'last_activity_at' => optional($incompleteOrder->last_activity_at)?->toDateTimeString(),
        ];
    }

    private function getSettings(): Settings
    {
        return Settings::firstOrNew();
    }

    private function canForwardFromSlave(Settings $settings): bool
    {
        return $this->isSlave($settings)
            && $settings->forwarding_enabled
            && ! empty($settings->forwarding_master_domain)
            && ! empty($settings->forwarding_master_secret);
    }

    private function isSlave(Settings $settings): bool
    {
        return ! empty(trim((string) $settings->forwarding_master_domain));
    }

    private function postJson(string $url, array $payload, string $secret): Response
    {
        Log::info('Incomplete HTTP POST', [
            'url' => $url,
            'payload' => $payload,
        ]);

        $http = Http::acceptJson()
            ->withHeaders([
                'X-Master-Secret' => $secret,
            ])
            ->withOptions([
                'allow_redirects' => [
                    'max' => 5,
                    'strict' => true,
                    'referer' => true,
                    'track_redirects' => true,
                ],
            ]);

        if (app()->environment('local') || str_contains($url, '.test') || str_contains($url, 'localhost')) {
            $http = $http->withoutVerifying();
        }

        /** @var Response $response */
        $response = $http->post($url, $payload);

        Log::info('Incomplete HTTP POST response', [
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

        return 'http://'.rtrim($url, '/');
    }
}
