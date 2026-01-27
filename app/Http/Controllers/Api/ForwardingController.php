<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ManualOrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Models\User;
use App\Services\OrderForwardingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

        Log::info('Forwarding receiveOrder payload accepted', [
            'slave_order_id' => $payload['slave_order_id'],
            'slave_domain' => $payload['slave_domain'],
            'item_count' => count($payload['items'] ?? []),
        ]);

        // Deduplicate: if already created, return existing master_id
        $existing = Order::query()
            ->where('slave_id', $payload['slave_order_id'])
            ->where('slave_domain', $payload['slave_domain'])
            ->where('forwarding_status', OrderForwardingService::STATUS_SUCCESS)
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

            $product = Product::query()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->first();

            if (! $product && $sku !== '') {
                $product = Product::query()->where('sku', $sku)->first();
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

        ManualOrderType::query()->firstOrCreate(['name' => $payload['slave_domain']]);

        /** @var Order $order */
        $order = DB::transaction(function () use ($request, $payload, $statusCode, $resolvedItems) {
            $assignee = $this->selectAssignee();

            $customer = $payload['customer'] ?? [];

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
            $order->order_type = $payload['slave_domain'];
            $order->ip_address = $request->ip();
            $order->slave_id = $payload['slave_order_id'];
            $order->slave_domain = $payload['slave_domain'];
            $order->forwarding_status = OrderForwardingService::STATUS_SUCCESS;
            $order->coming = $payload['coming'] ?? 0;
            $order->save();

            foreach ($resolvedItems as $item) {
                $cart = new Cart;
                $cart->order_id = $order->id;
                $cart->product_id = $item['product_id'];
                $cart->quantity = $item['quantity'];
                $cart->price = $item['price'];
                $cart->save();
            }

            return $order;
        });

        Log::info('Forwarding receiveOrder created master order', [
            'master_id' => $order->id,
            'slave_order_id' => $payload['slave_order_id'],
            'slave_domain' => $payload['slave_domain'],
        ]);

        return response()->json([
            'success' => true,
            'master_id' => $order->id,
        ], 201);
    }

    private function selectAssignee(): ?int
    {
        $priority = [
            ['role' => 3, 'status' => 1], // Active Employee
            ['role' => 2, 'status' => 1], // Active Manager
            ['role' => 1, 'status' => 1], // Active Admin
            ['role' => 3, 'status' => null], // Inactive Employee
            ['role' => 2, 'status' => null], // Inactive Manager
            ['role' => 1, 'status' => null], // Inactive Admin
        ];

        foreach ($priority as $criteria) {
            $query = User::query()->where('role', $criteria['role']);

            if ($criteria['status'] !== null) {
                $query->where('status', $criteria['status']);
            }

            $id = $query->inRandomOrder()->value('id');

            if ($id) {
                return (int) $id;
            }
        }

        return null;
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

        $settings = Settings::first();
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

            if (! $order || (int) $order->master_id !== (int) $request->input('master_id')) {
                Log::warning('Forwarding receiveStatus order not found on slave', [
                    'master_id' => $request->input('master_id'),
                    'slave_order_id' => $request->input('slave_order_id'),
                ]);

                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }
        }

        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
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
}
