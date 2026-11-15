<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ManualOrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Services\OrderAssigneeService;
use App\Services\OrderForwardingService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForwardingController extends Controller
{
    public function receiveOrder(Request $request, OrderForwardingService $forwarder): JsonResponse
    {
        if (! $forwarder->isValidSecret($request->header('X-Master-Secret'))) {
            Log::warning('Forwarding receiveOrder unauthorized', ['ip' => $request->ip()]);

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'slave_order_id' => ['required', 'integer'],
            'slave_domain' => ['required', 'string'],
            'customer' => ['array'],
            'attribution' => ['nullable', 'array'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required'],
            'coming' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            Log::warning('Forwarding receiveOrder validation failed', [
                'errors' => $validator->errors()->toArray(),
            ]);

            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $validator->validated();
        $rawDomain = trim((string) $payload['slave_domain']);
        $normalizedDomain = $this->normalizeDomain($rawDomain);

        Log::info('Forwarding receiveOrder payload accepted', [
            'slave_order_id' => $payload['slave_order_id'],
            'slave_domain' => $payload['slave_domain'],
            'normalized_domain' => $normalizedDomain,
            'item_count' => count($payload['items'] ?? []),
        ]);

        // Deduplicate: if already created on master, return existing master_id
        $existing = Order::query()
            ->where('slave_id', $payload['slave_order_id'])
            ->where(function ($query) use ($rawDomain, $normalizedDomain) {
                $query->where('slave_domain', $rawDomain)
                    ->orWhere('slave_domain', $normalizedDomain)
                    ->orWhere('slave_domain', 'www.'.$normalizedDomain)
                    ->orWhere('slave_domain', 'like', '%'.$normalizedDomain.'%');
            })
            ->first();

        if ($existing) {
            Log::info('Forwarding receiveOrder duplicate detected', [
                'slave_order_id' => $payload['slave_order_id'],
                'slave_domain' => $payload['slave_domain'],
                'master_id' => $existing->id,
            ]);

            return response()->json([
                'success' => true,
                'master_id' => $existing->id,
                'message' => 'Order already exists on master',
            ]);
        }

        $statusCode = $forwarder->statusCodeFromString($request->input('status'));
        if ($statusCode === null) {
            $statusCode = Order::STATUS_PROCESSING;
        }

        $missingProducts = [];
        $resolvedItems = [];

        foreach ($payload['items'] as $item) {
            $name = trim((string) $item['product_name']);
            $sku = trim((string) ($item['sku'] ?? ''));
            $slug = trim((string) ($item['slug'] ?? ''));

            $product = null;

            // 1. By SKU if provided
            if ($sku !== '') {
                $product = Product::query()->whereRaw('LOWER(sku) = ?', [mb_strtolower($sku)])->first();
            }

            // 2. By slug if provided
            if (! $product && $slug !== '') {
                $product = Product::query()->where('slug', $slug)->first();
            }

            // 3. By exact name (case-insensitive)
            if (! $product) {
                $product = Product::query()->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();
            }

            // 4. By normalized whitespace
            $normalizedName = trim((string) preg_replace('/\s+/', ' ', $name));
            if (! $product && $normalizedName !== $name) {
                $product = Product::query()->whereRaw('LOWER(name) = ?', [mb_strtolower($normalizedName)])->first();
            }

            // 5. Try stripped prefix/suffix (e.g. '1 - ps Jaysuing Advanced Scar Gel' -> 'Jaysuing Advanced Scar Gel')
            if (! $product) {
                $strippedName = preg_replace('/^\s*\d+\s*[-–x]?\s*(ps|pcs|pc|piece|pieces)?\s*[-–]?\s*/i', '', $normalizedName);
                $strippedName = preg_replace('/\s*[-–]\s*\d+\s*(ps|pcs|pc|piece|pieces)?\s*$/i', '', (string) $strippedName);
                $strippedName = trim((string) $strippedName);

                if ($strippedName !== '' && $strippedName !== $normalizedName) {
                    $product = Product::query()->whereRaw('LOWER(name) = ?', [mb_strtolower($strippedName)])->first();
                    if (! $product) {
                        $product = Product::query()->where('name', 'LIKE', '%'.$strippedName.'%')->first();
                    }
                }
            }

            // 6. Partial match
            if (! $product) {
                $product = Product::query()->where('name', 'LIKE', '%'.$normalizedName.'%')->first();
            }

            // 7. Auto-create product on master so order forwarding never gets rejected
            if (! $product) {
                try {
                    $categoryId = Category::value('id') ?? 1;
                    $productSlug = ! empty($slug) ? $slug : Str::slug($name);
                    if (Product::where('slug', $productSlug)->exists()) {
                        $productSlug .= '-'.time();
                    }

                    $product = Product::create([
                        'name' => $name,
                        'slug' => $productSlug,
                        'sku' => ! empty($sku) ? $sku : 'SKU-'.strtoupper(Str::random(6)),
                        'category_id' => $categoryId,
                        'regular_price' => (float) ($item['price'] ?? 1),
                        'offer_price' => (float) ($item['price'] ?? 1),
                        'status' => 1,
                    ]);

                    Log::info('Forwarding receiveOrder auto-created missing product on master', [
                        'slave_order_id' => $payload['slave_order_id'],
                        'product_id' => $product->id,
                        'product_name' => $name,
                    ]);
                } catch (\Throwable $ex) {
                    Log::warning('Forwarding receiveOrder failed to auto-create product', [
                        'name' => $name,
                        'error' => $ex->getMessage(),
                    ]);
                }
            }

            if (! $product) {
                $missingProducts[] = [
                    'name' => $name,
                    'sku' => $sku,
                ];

                continue;
            }

            $resolvedItems[] = [
                'product_id' => $product->id,
                'name' => $name,
                'quantity' => (int) $item['quantity'],
                'price' => (int) $item['price'],
                'package' => $item['package'] ?? null,
                'attribute' => $item['attribute'] ?? null,
            ];
        }

        if ($missingProducts !== []) {
            Log::warning('Forwarding receiveOrder missing products', [
                'slave_order_id' => $payload['slave_order_id'],
                'missing' => $missingProducts,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Products not matched on master site',
                'missing_products' => $missingProducts,
            ], 422);
        }

        ManualOrderType::query()->firstOrCreate(['name' => $normalizedDomain]);

        // Gather product IDs for assignment logic
        $productIds = array_column($resolvedItems, 'product_id');

        $assigneeService = new OrderAssigneeService;

        try {
            /** @var Order $order */
            $order = DB::transaction(function () use ($request, $payload, $normalizedDomain, $statusCode, $resolvedItems, $productIds, $assigneeService) {
                $assignee = $assigneeService->selectAssignee($productIds);

                $customer = $payload['customer'] ?? [];
                $attribution = is_array($request->input('attribution')) ? $request->input('attribution') : [];

                $order = new Order;
                $order->name = $customer['name'] ?? null;
                $order->phone = $customer['phone'] ?? null;
                $order->email = $customer['email'] ?? null;
                $order->address = $customer['address'] ?? null;
                $order->city = $customer['city'] ?? null;
                $order->zone = $customer['zone'] ?? null;
                $order->order_assign = $assignee;

                $order->shipping_cost = $request->input('shipping_cost');
                $order->discount = $request->input('discount');
                $order->sub_total = $request->input('sub_total');
                $order->total = $request->input('total');
                $order->order_note = $request->input('order_note');
                $order->status = $statusCode;
                $order->order_type = $normalizedDomain;
                $order->ip_address = $request->ip();
                $order->utm_source = $attribution['utm_source'] ?? null;
                $order->utm_medium = $attribution['utm_medium'] ?? null;
                $order->utm_campaign = $attribution['utm_campaign'] ?? null;
                $order->fbp = $attribution['fbp'] ?? null;
                $order->fbc = $attribution['fbc'] ?? null;
                $order->slave_id = $payload['slave_order_id'];
                $order->slave_domain = $normalizedDomain;
                $order->forwarding_status = OrderForwardingService::STATUS_SUCCESS;
                $order->coming = $payload['coming'] ?? 0;
                $order->save();

                foreach ($resolvedItems as $item) {
                    $cart = new Cart;
                    $cart->order_id = $order->id;
                    $cart->product_id = $item['product_id'];
                    $cart->quantity = $item['quantity'];
                    $cart->price = $item['price'];
                    if (isset($item['package'])) {
                        $cart->package = $item['package'];
                    }
                    if (isset($item['attribute'])) {
                        $cart->attribute = $item['attribute'];
                    }
                    $cart->save();
                }

                return $order;
            });
        } catch (QueryException $e) {
            // Handle duplicate entry constraint gracefully (e.g. race condition or re-forwarding)
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'Duplicate entry')) {
                $existing = Order::query()
                    ->where('slave_id', $payload['slave_order_id'])
                    ->first();

                if ($existing) {
                    Log::info('Forwarding receiveOrder caught duplicate key, returning existing master_id', [
                        'slave_order_id' => $payload['slave_order_id'],
                        'master_id' => $existing->id,
                    ]);

                    return response()->json([
                        'success' => true,
                        'master_id' => $existing->id,
                        'message' => 'Order already exists on master',
                    ]);
                }
            }

            throw $e;
        }

        Log::info('Forwarding receiveOrder created master order', [
            'master_id' => $order->id,
            'slave_order_id' => $payload['slave_order_id'],
            'slave_domain' => $normalizedDomain,
        ]);

        return response()->json([
            'success' => true,
            'master_id' => $order->id,
        ], 201);
    }

    public function receiveStatus(Request $request, OrderForwardingService $forwarder): JsonResponse
    {
        if (! $forwarder->isValidSecret($request->header('X-Master-Secret'))) {
            Log::warning('Forwarding receiveStatus unauthorized', ['ip' => $request->ip()]);

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'master_id' => ['required', 'integer'],
            'slave_order_id' => ['required', 'integer'],
            'status' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            Log::warning('Forwarding receiveStatus validation failed', [
                'errors' => $validator->errors()->toArray(),
            ]);

            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $statusCode = $forwarder->statusCodeFromString($request->input('status'));

        if ($statusCode === null) {
            Log::warning('Forwarding receiveStatus unknown status', [
                'status' => $request->input('status'),
            ]);

            return response()->json(['success' => false, 'message' => 'Unknown status'], 422);
        }

        $settings = Settings::getSettings();
        $isMaster = $settings ? empty(trim((string) $settings->forwarding_master_domain)) : true;

        if ($isMaster) {
            $order = Order::find($request->input('master_id'));

            if (! $order || (int) $order->slave_id !== (int) $request->input('slave_order_id')) {
                Log::warning('Forwarding receiveStatus order not found on master', [
                    'master_id' => $request->input('master_id'),
                    'slave_order_id' => $request->input('slave_order_id'),
                ]);

                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }
        } else {
            $order = Order::find($request->input('slave_order_id'));

            if (! $order) {
                Log::warning('Forwarding receiveStatus order not found on slave', [
                    'master_id' => $request->input('master_id'),
                    'slave_order_id' => $request->input('slave_order_id'),
                ]);

                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Self-heal: If slave order has no master_id, set it now!
            if (empty($order->master_id)) {
                $order->master_id = (int) $request->input('master_id');
                $order->forwarding_status = OrderForwardingService::STATUS_SUCCESS;
                $order->forwarding_error = null;
                $order->saveQuietly();

                Log::info('Forwarding receiveStatus self-healed master_id on slave', [
                    'order_id' => $order->id,
                    'master_id' => $order->master_id,
                ]);
            } elseif ((int) $order->master_id !== (int) $request->input('master_id')) {
                Log::warning('Forwarding receiveStatus master_id mismatch on slave', [
                    'order_id' => $order->id,
                    'slave_stored_master_id' => $order->master_id,
                    'incoming_master_id' => $request->input('master_id'),
                ]);

                return response()->json(['success' => false, 'message' => 'Order master ID mismatch'], 404);
            }
        }

        DB::transaction(function () use ($order, $statusCode): void {
            Order::withoutEvents(function () use ($order, $statusCode): void {
                $order->status = $statusCode;
                $order->save();
            });
        });

        Log::info('Forwarding receiveStatus applied', [
            'order_id' => $order->id,
            'status_code' => $statusCode,
        ]);

        return response()->json(['success' => true]);
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = trim($domain);
        $domain = preg_replace('~^https?://~i', '', $domain);
        $domain = explode('/', $domain)[0];
        $domain = preg_replace('/:\d+$/', '', (string) $domain);
        $domain = preg_replace('/^www\./i', '', (string) $domain);

        return strtolower((string) $domain);
    }
}
}
