<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Landing\StoreLandingOrderWebhookRequest;
use App\Models\Cart;
use App\Models\ManualOrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderForwardingService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class LandingOrderController extends Controller
{
    public function __invoke(
        StoreLandingOrderWebhookRequest $request,
        WhatsAppService $whatsAppService,
    ): JsonResponse {
        $payload = $request->validated();

        $sourceHref = (string) data_get($payload, '_links.self.0.href');
        $domain = $this->extractDomainFromUrl($sourceHref);

        $this->ensureManualOrderTypeExists($domain);

        /** @var array<string, mixed> $billing */
        $billing = $payload['billing'] ?? [];

        $name = trim(implode(' ', array_filter([
            (string) ($billing['first_name'] ?? ''),
            (string) ($billing['last_name'] ?? ''),
        ])));

        $addressParts = array_values(array_filter([
            (string) ($billing['address_1'] ?? ''),
            (string) ($billing['address_2'] ?? ''),
            (string) ($billing['city'] ?? ''),
        ]));
        $address = trim(implode(', ', $addressParts));

        $ipAddress = (string) ($payload['customer_ip_address'] ?? $request->ip());

        /** @var array<int, array<string, mixed>> $lineItems */
        $lineItems = $payload['line_items'] ?? [];

        $lineItemNameToProductId = [];
        $missingProducts = [];

        foreach ($lineItems as $item) {
            $resolvedProductId = $this->resolveLocalProductIdFromLineItem($item, $lineItemNameToProductId);

            if ($resolvedProductId === null) {
                $missingProducts[] = [
                    'name' => (string) ($item['name'] ?? ''),
                    'sku' => (string) ($item['sku'] ?? ''),
                ];
            } else {
                $itemName = trim((string) ($item['name'] ?? ''));
                if ($itemName !== '') {
                    $lineItemNameToProductId[$this->normalizeProductKey($itemName)] = $resolvedProductId;
                }
            }
        }

        if ($missingProducts !== []) {
            throw ValidationException::withMessages([
                'line_items' => ['Some products could not be matched to local products.'],
                'missing_products' => [json_encode($missingProducts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)],
            ]);
        }

        $computedSubTotal = 0.0;
        foreach ($lineItems as $item) {
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            $lineTotalValue = $item['total'] ?? $item['subtotal'] ?? null;
            if ($lineTotalValue !== null) {
                $computedSubTotal += $this->normalizeMoney($lineTotalValue);

                continue;
            }

            $unitPrice = $this->normalizeMoney($item['price'] ?? 0);
            $computedSubTotal += $unitPrice * $quantity;
        }

        $shippingCost = $this->normalizeMoney($payload['shipping_total'] ?? 0);
        $discount = $this->normalizeMoney($payload['discount_total'] ?? 0);
        $total = $this->normalizeMoney($payload['total'] ?? ($computedSubTotal + $shippingCost));
        $subTotal = $computedSubTotal > 0 ? $computedSubTotal : max(0, $total - $shippingCost);

        $cartHasColor = Schema::hasColumn('carts', 'color');
        $cartHasSize = Schema::hasColumn('carts', 'size');
        $cartHasModel = Schema::hasColumn('carts', 'model');

        /** @var Order $order */
        $order = DB::transaction(function () use (
            $name,
            $address,
            $billing,
            $ipAddress,
            $shippingCost,
            $subTotal,
            $total,
            $discount,
            $payload,
            $lineItems,
            $cartHasColor,
            $cartHasSize,
            $cartHasModel,
            $domain,
            $lineItemNameToProductId,
        ) {
            $assignee = User::query()
                ->where('status', 1)
                ->where('role', 3)
                ->inRandomOrder()
                ->first();

            $order = new Order;
            $order->name = $name !== '' ? $name : null;
            $order->address = $address !== '' ? $address : null;
            $order->phone = trim((string) ($billing['phone'] ?? '')) ?: null;
            $order->email = $billing['email'] ?? null;
            $order->city = $billing['city'] ?? null;
            $order->order_assign = $assignee?->id;

            $order->payment_method = $payload['payment_method'] ?? 'cod';
            $order->shipping_cost = $this->formatMoney($shippingCost);
            $order->discount = $this->formatMoney($discount);
            $order->sub_total = $this->formatMoney($subTotal);
            $order->total = $this->formatMoney($total);

            $order->order_type = $domain;
            $order->ip_address = $ipAddress;
            $order->order_note = $payload['customer_note'] ?? null;
            $order->save();

            foreach ($lineItems as $item) {
                $itemName = trim((string) ($item['name'] ?? ''));
                $normalizedKey = $this->normalizeProductKey($itemName);
                $localProductId = $lineItemNameToProductId[$normalizedKey] ?? null;

                if (! is_int($localProductId)) {
                    throw ValidationException::withMessages([
                        'line_items' => ['Product mapping failed while creating carts.'],
                        'name' => [$itemName],
                    ]);
                }

                $cart = new Cart;
                $cart->order_id = $order->id;
                $cart->product_id = $localProductId;

                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $cart->quantity = $quantity;

                $unitPrice = $this->normalizeMoney($item['price'] ?? 0);
                if ($unitPrice <= 0) {
                    $lineTotal = $this->normalizeMoney($item['total'] ?? $item['subtotal'] ?? 0);
                    $unitPrice = $quantity > 0 ? ($lineTotal / $quantity) : 0.0;
                }

                $cart->price = (int) round($unitPrice);
                $cart->ip_address = $ipAddress;

                [$color, $size, $model] = $this->extractCartOptionsFromMeta($item['meta_data'] ?? []);
                if ($cartHasColor && $color !== null) {
                    $cart->color = $color;
                }
                if ($cartHasSize && $size !== null) {
                    $cart->size = $size;
                }
                if ($cartHasModel && $model !== null) {
                    $cart->model = $model;
                }

                $cart->save();
            }

            return $order;
        });

        $whatsAppService->sendOrderNotification($order);

        // Forward to master immediately if configured (slave mode only)
        app(OrderForwardingService::class)->forwardOrder($order);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'order_type' => $domain,
            'source' => $sourceHref,
        ], 201);
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, int>  $cache
     */
    private function resolveLocalProductIdFromLineItem(array $item, array $cache): ?int
    {
        $name = trim((string) ($item['name'] ?? ''));
        $sku = trim((string) ($item['sku'] ?? ''));

        if ($name !== '') {
            $cacheKey = $this->normalizeProductKey($name);
            if (isset($cache[$cacheKey])) {
                return $cache[$cacheKey];
            }
        }

        if ($sku !== '') {
            $product = Product::query()->where('sku', $sku)->first();
            if ($product) {
                return (int) $product->id;
            }
        }

        if ($name === '') {
            return null;
        }

        $slug = Str::slug($name);

        $product = Product::query()
            ->where('name', $name)
            ->orWhere('slug', $slug)
            ->first();

        if ($product) {
            return (int) $product->id;
        }

        $lower = mb_strtolower($name);

        $product = Product::query()
            ->whereRaw('LOWER(name) = ?', [$lower])
            ->first();

        if ($product) {
            return (int) $product->id;
        }

        $safeLike = addcslashes($name, '%_\\');

        $product = Product::query()
            ->where('name', 'like', '%'.$safeLike.'%')
            ->first();

        return $product ? (int) $product->id : null;
    }

    private function normalizeProductKey(string $name): string
    {
        return mb_strtolower(trim($name));
    }

    private function extractDomainFromUrl(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        $host = is_string($host) ? trim($host) : '';

        if ($host === '') {
            return Order::TYPE_MANUAL;
        }

        return $host;
    }

    private function ensureManualOrderTypeExists(string $domain): void
    {
        if ($domain === '' || $domain === Order::TYPE_MANUAL) {
            return;
        }

        $existing = ManualOrderType::query()->where('name', $domain)->first();
        if ($existing) {
            return;
        }

        $maxSortOrder = ManualOrderType::max('sort_order') ?? 0;

        ManualOrderType::create([
            'name' => $domain,
            'status' => true,
            'sort_order' => $maxSortOrder + 1,
        ]);
    }

    private function normalizeMoney(mixed $value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $string = is_string($value) ? $value : (string) $value;
        $string = preg_replace('~[^0-9.\-]~', '', $string) ?? '';

        return (float) $string;
    }

    private function formatMoney(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * @return array{0: ?string, 1: ?string, 2: ?string}
     */
    private function extractCartOptionsFromMeta(mixed $metaData): array
    {
        if (! is_array($metaData)) {
            return [null, null, null];
        }

        $color = null;
        $size = null;
        $model = null;

        foreach ($metaData as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $key = strtolower((string) ($entry['key'] ?? ''));
            $value = $entry['value'] ?? null;
            $valueString = is_string($value) ? trim($value) : null;

            if ($valueString === null || $valueString === '') {
                continue;
            }

            if (in_array($key, ['color', 'pa_color', 'attribute_pa_color'], true)) {
                $color = $valueString;
            }

            if (in_array($key, ['size', 'pa_size', 'attribute_pa_size'], true)) {
                $size = $valueString;
            }

            if (in_array($key, ['model', 'pa_model', 'attribute_pa_model'], true)) {
                $model = $valueString;
            }
        }

        return [$color, $size, $model];
    }
}
