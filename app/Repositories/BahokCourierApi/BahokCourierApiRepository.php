<?php

declare(strict_types=1);

namespace App\Repositories\BahokCourierApi;

use App\Models\Order;
use App\Models\Settings;
use App\Services\QuantityMonitorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BahokCourierApiRepository implements BahokCourierApiInterface
{
    private function getBaseUrl(): string
    {
        return rtrim((string) config('courier.bahok_merchant.base_url', 'https://api.bahokcourier.com'), '/');
    }

    private function getCredentials(): array
    {
        $settings = Settings::getSettings();

        return [
            'client_id' => ! empty($settings->bahok_client_id)
                ? $settings->bahok_client_id
                : config('courier.bahok_merchant.client_id', 'bK8mepphokeMy'),
            'client_secret' => ! empty($settings->bahok_client_secret)
                ? $settings->bahok_client_secret
                : config('courier.bahok_merchant.client_secret', 'ZK21kv1S1yhHD5hjs1u7ltIjkXPFDPFTIonmRF7s'),
            'username' => ! empty($settings->bahok_username)
                ? $settings->bahok_username
                : config('courier.bahok_merchant.username', ''),
            'password' => ! empty($settings->bahok_password)
                ? $settings->bahok_password
                : config('courier.bahok_merchant.password', ''),
        ];
    }

    /**
     * Authenticate and obtain an access token.
     */
    public function getAccessToken(bool $forceRefresh = false): ?string
    {
        $cacheKey = 'bahok_courier_access_token';

        if (! $forceRefresh) {
            $cachedToken = Cache::get($cacheKey);
            if ($cachedToken) {
                return (string) $cachedToken;
            }
        }

        $credentials = $this->getCredentials();
        $baseUrl = $this->getBaseUrl();

        if (empty($credentials['client_id']) || empty($credentials['client_secret']) || empty($credentials['username']) || empty($credentials['password'])) {
            Log::warning('Bahok Courier credentials are incomplete.');

            return null;
        }

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($baseUrl.'/api/v1/baymax/issue-token', [
                    'client_id' => $credentials['client_id'],
                    'client_secret' => $credentials['client_secret'],
                    'username' => $credentials['username'],
                    'password' => $credentials['password'],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['data']['access_token'] ?? null;
                $expiresIn = (int) ($data['data']['expires_in'] ?? 3600);

                if ($accessToken) {
                    Cache::put($cacheKey, $accessToken, now()->addSeconds(max(60, $expiresIn - 120)));

                    return (string) $accessToken;
                }
            }

            Log::error('Bahok Courier Token Issue Failed: '.$response->body());

            return null;
        } catch (\Throwable $e) {
            Log::error('Bahok Courier Token Issue Exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Create a delivery order in Bahok Courier.
     */
    public function createOrder(?Request $request, Order $order)
    {
        $token = $this->getAccessToken();

        if (! $token) {
            return (object) [
                'success' => false,
                'code' => 401,
                'message' => 'Failed to obtain access token from Bahok Courier. Please verify merchant credentials.',
            ];
        }

        // Bahok strictly requires 11-digit phone number (e.g. 01XXXXXXXXX)
        $phone = preg_replace('/[^\d]/', '', (string) ($order->phone ?? ''));
        if (str_starts_with($phone, '8801')) {
            $phone = substr($phone, 2);
        } elseif (str_starts_with($phone, '+8801')) {
            $phone = substr($phone, 3);
        }

        // Name length: 3 to 100 characters
        $name = trim((string) ($order->name ?? 'Customer'));
        if (strlen($name) < 3) {
            $name = str_pad($name, 3, '.');
        }
        $name = substr($name, 0, 100);

        // Address length: 10 to 220 characters
        $address = trim((string) ($order->address ?? 'Bangladesh'));
        if (strlen($address) < 10) {
            $address = str_pad($address, 10, ' ');
        }
        $address = substr($address, 0, 220);

        // Collectible COD amount
        $amountToCollect = (int) round((float) ($order->total ?? 0));

        // Build item description from items if available
        $itemDescription = '';
        if ($order->relationLoaded('many_cart') || $order->relationLoaded('items')) {
            $items = $order->many_cart ?? $order->items;
            if ($items && $items->isNotEmpty()) {
                $itemDescription = $items->map(function ($item) {
                    $prodName = $item->product->name ?? 'Product';
                    $qty = $item->quantity ?? 1;

                    return "{$prodName} (x{$qty})";
                })->implode(', ');
            }
        }
        if (empty($itemDescription)) {
            $itemDescription = 'Standard Delivery';
        }

        $payload = [
            'merchant_order_id' => (string) $order->id,
            'recipient_name' => $name,
            'recipient_phone' => $phone,
            'recipient_address' => $address,
            'amount_to_collect' => $amountToCollect,
            'special_instruction' => (string) ($order->order_note ?? ''),
            'item_description' => substr($itemDescription, 0, 250),
            'is_exchange' => 2,
        ];

        $baseUrl = $this->getBaseUrl();

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ])
                ->post($baseUrl.'/api/v1/baymax/orders/store', $payload);

            // If token expired, force refresh once and retry
            if ($response->status() === 401) {
                $token = $this->getAccessToken(true);
                if ($token) {
                    $response = Http::withoutVerifying()
                        ->withHeaders([
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer '.$token,
                        ])
                        ->post($baseUrl.'/api/v1/baymax/orders/store', $payload);
                }
            }

            Log::info('Bahok Courier Create Order Response', [
                'order_id' => $order->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable $e) {
            Log::error('Bahok Courier Create Order Exception: '.$e->getMessage());

            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create multiple delivery orders in a single request.
     */
    public function bulkCreateOrder(array $orders)
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return false;
        }

        $baseUrl = $this->getBaseUrl();

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ])
                ->post($baseUrl.'/api/v1/baymax/orders/bulk-store', [
                    'orders' => $orders,
                ]);

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable $e) {
            Log::error('Bahok Courier Bulk Create Order Exception: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Retrieve short info about an order by consignment ID.
     */
    public function getOrderShortInfo(string|int $consignmentId)
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return false;
        }

        $baseUrl = $this->getBaseUrl();

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ])
                ->get($baseUrl.'/api/v1/baymax/orders/short-info/'.$consignmentId);

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable $e) {
            Log::error('Bahok Courier Get Short Info Exception: '.$e->getMessage());

            return false;
        }
    }

    public function getDistricts()
    {
        return $this->getEndpointData('/api/v1/baymax/districts');
    }

    public function getThanas(?int $districtId = null)
    {
        $query = $districtId ? '?district_id='.$districtId : '';

        return $this->getEndpointData('/api/v1/baymax/thanas'.$query);
    }

    public function getAreas(?int $thanaId = null)
    {
        $query = $thanaId ? '?thana_id='.$thanaId : '';

        return $this->getEndpointData('/api/v1/baymax/areas'.$query);
    }

    public function getHubs()
    {
        return $this->getEndpointData('/api/v1/baymax/hubs');
    }

    public function getStatuses()
    {
        return $this->getEndpointData('/api/v1/baymax/statuses');
    }

    private function getEndpointData(string $endpoint)
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return false;
        }

        $baseUrl = $this->getBaseUrl();

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ])
                ->get($baseUrl.$endpoint);

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable $e) {
            Log::error("Bahok Courier Endpoint Exception ({$endpoint}): ".$e->getMessage());

            return false;
        }
    }

    /**
     * Process webhook status update from Bahok Courier.
     */
    public function statusUpdate($request): bool
    {
        try {
            Log::info('Bahok webhook received', ['payload' => $request->all()]);

            // Webhook auth token verification using Bahok Client ID
            $settings = Settings::getSettings();
            $configuredToken = $settings->bahok_client_id ?: config('courier.bahok_merchant.client_id');
            if (! empty($configuredToken)) {
                $incomingToken = $request->bearerToken()
                    ?: $request->header('X-Webhook-Token')
                    ?: $request->header('X-Bahok-Token')
                    ?: $request->header('Webhook-Auth-Token')
                    ?: $request->input('token')
                    ?: $request->input('auth_token');

                if ($incomingToken && ! hash_equals((string) $configuredToken, (string) $incomingToken)) {
                    Log::warning('Bahok webhook auth token mismatch', [
                        'received' => $incomingToken,
                    ]);

                    return false;
                }
            }

            $consignmentId = $request->id ?? $request->consignment_id ?? null;
            $merchantOrderId = $request->ref_id ?? $request->merchant_order_id ?? null;

            $order = null;
            if (! empty($consignmentId)) {
                $order = Order::where('consignment_id', (string) $consignmentId)->first();
            }

            if (! $order && ! empty($merchantOrderId)) {
                $cleanId = preg_replace('/[^0-9]/', '', (string) $merchantOrderId);
                if (! empty($cleanId)) {
                    $order = Order::find($cleanId);
                }
                if (! $order) {
                    $order = Order::where('id', $merchantOrderId)->first();
                }
            }

            if (! $order) {
                Log::warning('Bahok webhook order not found', [
                    'id' => $consignmentId,
                    'ref_id' => $merchantOrderId,
                ]);

                return false;
            }

            if (! empty($consignmentId) && empty($order->consignment_id)) {
                $order->consignment_id = (string) $consignmentId;
            }

            $order->courier = 14;

            if (! empty($request->payment_status)) {
                $order->payment_status = $request->payment_status;
            }

            $event = strtolower(trim((string) ($request->event ?? '')));
            $statusName = trim((string) ($request->status ?? ''));
            $statusId = (int) ($request->status_id ?? 0);

            $this->applyStatusUpdate($order, $event, $statusId, $statusName);

            $order->save();

            if (class_exists(QuantityMonitorService::class)) {
                app(QuantityMonitorService::class)->updateDeliveredQuantity($order);
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Bahok webhook exception: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    private function applyStatusUpdate(Order $order, string $event, int $statusId, string $statusName): void
    {
        $normalizedStatus = strtolower($statusName);

        // Pick requested
        if ($event === 'order.pick_requested' || $statusId === 1 || $normalizedStatus === 'pick requested') {
            $order->courier_status = 'Pick Requested';

            return;
        }

        // Pick request cancel
        if ($event === 'order.pick_request_cancel' || $statusId === 2 || $normalizedStatus === 'pick request cancel') {
            $order->status = Order::STATUS_CANCEL;
            $order->courier_status = 'Pick Request Cancel';

            return;
        }

        // Picked up
        if ($event === 'order.picked' || $statusId === 3 || $normalizedStatus === 'picked') {
            if (in_array((int) $order->status, [Order::STATUS_PENDING_DELIVERY, Order::STATUS_TOTAL_DELIVERY, Order::STATUS_PROCESSING], true)) {
                $order->status = Order::STATUS_ON_DELIVERY;
            }
            $order->courier_status = 'Picked';

            return;
        }

        // At sorting hub
        if ($event === 'order.at_sorting_hub' || $statusId === 4 || $normalizedStatus === 'at sorting hub') {
            if (in_array((int) $order->status, [Order::STATUS_PENDING_DELIVERY, Order::STATUS_TOTAL_DELIVERY, Order::STATUS_PROCESSING], true)) {
                $order->status = Order::STATUS_ON_DELIVERY;
            }
            $order->courier_status = 'At Sorting Hub';

            return;
        }

        // At destination hub
        if ($event === 'order.at_destination_hub' || $statusId === 5 || $normalizedStatus === 'at destination hub') {
            if (in_array((int) $order->status, [Order::STATUS_PENDING_DELIVERY, Order::STATUS_TOTAL_DELIVERY, Order::STATUS_PROCESSING], true)) {
                $order->status = Order::STATUS_ON_DELIVERY;
            }
            $order->courier_status = 'At Destination Hub';

            return;
        }

        // Rider assigned
        if ($event === 'order.rider_assigned' || $statusId === 7 || $normalizedStatus === 'rider assigned') {
            if (in_array((int) $order->status, [Order::STATUS_PENDING_DELIVERY, Order::STATUS_TOTAL_DELIVERY, Order::STATUS_PROCESSING], true)) {
                $order->status = Order::STATUS_ON_DELIVERY;
            }
            $order->courier_status = 'Rider Assigned';

            return;
        }

        // In transit
        if ($event === 'order.in_transit' || $statusId === 10 || $normalizedStatus === 'in transit') {
            if (in_array((int) $order->status, [Order::STATUS_PENDING_DELIVERY, Order::STATUS_TOTAL_DELIVERY, Order::STATUS_PROCESSING], true)) {
                $order->status = Order::STATUS_ON_DELIVERY;
            }
            $order->courier_status = 'In Transit';

            return;
        }

        // On way
        if ($event === 'order.on_way' || $statusId === 11 || $normalizedStatus === 'on way') {
            if (in_array((int) $order->status, [Order::STATUS_PENDING_DELIVERY, Order::STATUS_TOTAL_DELIVERY, Order::STATUS_PROCESSING], true)) {
                $order->status = Order::STATUS_ON_DELIVERY;
            }
            $order->courier_status = 'On Way';

            return;
        }

        // Delivered
        if ($event === 'order.delivered' || $statusId === 14 || $normalizedStatus === 'delivered') {
            $order->status = Order::STATUS_COMPLETED;
            $order->courier_status = 'Delivered';

            return;
        }

        // Partial delivered
        if ($event === 'order.partial_delivered' || $statusId === 15 || $normalizedStatus === 'partial delivered') {
            $order->status = Order::STATUS_PARTIAL_DELIVERY;
            $order->courier_status = 'Partial Delivered';

            return;
        }

        // Damage or Lost
        if ($event === 'order.damage_lost' || $statusId === 16 || $normalizedStatus === 'damage/lost') {
            $order->status = Order::STATUS_CANCEL;
            $order->courier_status = 'Damage/Lost';

            return;
        }

        // Hold
        if ($event === 'order.hold' || $statusId === 17 || $statusId === 9 || $normalizedStatus === 'hold' || $normalizedStatus === 'hold for problem') {
            $order->status = Order::STATUS_COURIER_HOLD;
            $order->courier_status = 'Hold';

            return;
        }

        // Reschedule
        if ($event === 'order.reschedule' || $statusId === 18 || $normalizedStatus === 'reschedule') {
            $order->courier_status = 'Reschedule';

            return;
        }

        // Exchanged
        if ($event === 'order.exchanged' || $statusId === 19 || $normalizedStatus === 'exchanged') {
            $order->courier_status = 'Exchanged';

            return;
        }

        // Cancel
        if ($event === 'order.cancel' || $statusId === 20 || $statusId === 2 || $normalizedStatus === 'cancel' || $normalizedStatus === 'cancel request') {
            $order->status = Order::STATUS_CANCEL;
            $order->courier_status = 'Cancel';

            return;
        }

        // Return On way to Merchant
        if ($event === 'order.return_on_way_to_merchant' || $normalizedStatus === 'return on way to merchant') {
            $order->status = Order::STATUS_PENDING_RETURN;
            $order->courier_status = 'Return On way to Merchant';

            return;
        }

        // Merchant Return Received
        if ($event === 'order.merchant_return_received' || $normalizedStatus === 'merchant return received') {
            $order->status = Order::STATUS_ORDER_RETURN;
            $order->courier_status = 'Merchant Return Received';

            return;
        }

        // Paid
        if ($event === 'order.paid' || $statusId === 34 || $normalizedStatus === 'paid') {
            $order->status = Order::STATUS_COMPLETED;
            $order->courier_status = 'Paid';

            return;
        }

        // Fallback for custom or unmapped status
        if (! empty($statusName)) {
            $order->courier_status = $statusName;
        }
    }
}
