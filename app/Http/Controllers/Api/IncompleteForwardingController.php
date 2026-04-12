<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncompleteOrder;
use App\Services\IncompleteOrderForwardingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IncompleteForwardingController extends Controller
{
    public function receiveIncomplete(Request $request, IncompleteOrderForwardingService $forwarder): JsonResponse
    {
        if (! $forwarder->isValidSecret($request->header('X-Master-Secret'))) {
            Log::warning('Incomplete receive unauthorized', ['ip' => $request->ip()]);

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'slave_order_id' => ['required', 'integer'],
            'slave_domain' => ['required', 'string'],
            'token' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer'],
            'ip_address' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'shipping_method_label' => ['nullable', 'string'],
            'shipping_amount' => ['nullable'],
            'sub_total' => ['nullable'],
            'total' => ['nullable'],
            'product_id' => ['nullable', 'integer'],
            'product_slug' => ['nullable', 'string'],
            'cart_snapshot' => ['nullable'],
            'status' => ['nullable', 'integer'],
            'completed_at' => ['nullable'],
            'cancellation_reason' => ['nullable', 'string'],
            'last_activity_at' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $validator->validated();
        $settings = \App\Models\Settings::first();
        $isMaster = $settings ? empty(trim((string) $settings->forwarding_master_domain)) : true;

        if (! $isMaster) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $existing = IncompleteOrder::query()
            ->where('slave_id', $payload['slave_order_id'])
            ->where('slave_domain', $payload['slave_domain'])
            ->first();

        $cartSnapshot = $payload['cart_snapshot'] ?? [];
        if (is_string($cartSnapshot)) {
            $decoded = json_decode($cartSnapshot, true);
            $cartSnapshot = is_array($decoded) ? $decoded : [];
        }

        $completedAt = $payload['completed_at'] ?? null;
        $lastActivityAt = $payload['last_activity_at'] ?? null;

        /** @var IncompleteOrder $incomplete */
        $incomplete = DB::transaction(function () use ($existing, $payload, $cartSnapshot, $completedAt, $lastActivityAt) {
            $incomplete = $existing ?? new IncompleteOrder;

            $incomplete->token = $payload['token'] ?? $incomplete->token;
            $incomplete->user_id = $payload['user_id'] ?? $incomplete->user_id;
            $incomplete->ip_address = $payload['ip_address'] ?? $incomplete->ip_address;
            $incomplete->name = $payload['name'] ?? $incomplete->name;
            $incomplete->address = $payload['address'] ?? $incomplete->address;
            $incomplete->phone = $payload['phone'] ?? $incomplete->phone;
            $incomplete->shipping_method_label = $payload['shipping_method_label'] ?? $incomplete->shipping_method_label;
            $incomplete->shipping_amount = $payload['shipping_amount'] ?? $incomplete->shipping_amount;
            $incomplete->sub_total = $payload['sub_total'] ?? $incomplete->sub_total;
            $incomplete->total = $payload['total'] ?? $incomplete->total;
            $incomplete->product_id = $payload['product_id'] ?? $incomplete->product_id;
            $incomplete->product_slug = $payload['product_slug'] ?? $incomplete->product_slug;
            $incomplete->cart_snapshot = $cartSnapshot;
            $incomplete->status = $payload['status'] ?? $incomplete->status ?? 0;
            $incomplete->completed_at = $completedAt ? \Illuminate\Support\Carbon::parse($completedAt) : null;
            $incomplete->cancellation_reason = $payload['cancellation_reason'] ?? $incomplete->cancellation_reason;
            $incomplete->last_activity_at = $lastActivityAt ? \Illuminate\Support\Carbon::parse($lastActivityAt) : null;
            $incomplete->slave_id = $payload['slave_order_id'];
            $incomplete->slave_domain = $payload['slave_domain'];
            $incomplete->forwarding_status = IncompleteOrderForwardingService::STATUS_SUCCESS;
            $incomplete->forwarding_error = null;
            $incomplete->save();

            return $incomplete;
        });

        return response()->json([
            'success' => true,
            'master_id' => $incomplete->id,
        ], $existing ? 200 : 201);
    }

    public function syncAction(Request $request, IncompleteOrderForwardingService $forwarder): JsonResponse
    {
        if (! $forwarder->isValidSecret($request->header('X-Master-Secret'))) {
            Log::warning('Incomplete sync unauthorized', ['ip' => $request->ip()]);

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'action' => ['required', 'in:cancel,delete'],
            'master_id' => ['required', 'integer'],
            'slave_order_id' => ['required', 'integer'],
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $validator->validated();
        $settings = \App\Models\Settings::first();
        $isMaster = $settings ? empty(trim((string) $settings->forwarding_master_domain)) : true;

        if ($isMaster) {
            $order = IncompleteOrder::query()
                ->where('id', $payload['master_id'])
                ->where('slave_id', $payload['slave_order_id'])
                ->first();
        } else {
            $order = IncompleteOrder::query()
                ->where('id', $payload['slave_order_id'])
                ->where('master_id', $payload['master_id'])
                ->first();
        }

        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        if ($payload['action'] === 'delete') {
            $order->delete();

            return response()->json(['success' => true]);
        }

        $order->status = 1;
        $order->cancellation_reason = $payload['cancellation_reason'] ?? $order->cancellation_reason;
        $order->save();

        return response()->json(['success' => true]);
    }
}