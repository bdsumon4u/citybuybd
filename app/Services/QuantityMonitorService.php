<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

class QuantityMonitorService
{
    /**
     * Update ordered_quantity based on cart products assigned to the order.
     * This should be called after all cart items have been assigned to the order.
     */
    public function updateOrderedQuantity(Order $order): Order
    {
        $cartItems = Cart::where('order_id', $order->id)
            ->get(['product_id', 'quantity']);

        $totalQuantity = (int) $cartItems->sum('quantity');
        $snapshotProductIds = $cartItems
            ->pluck('product_id')
            ->filter(fn ($id) => ! empty($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $updateData = ['ordered_quantity' => $totalQuantity];

        // Keep first order-time product set for replacement-based xSell checks.
        if (empty($order->ordered_product_ids)) {
            $updateData['ordered_product_ids'] = $snapshotProductIds;
        }

        $order->update($updateData);

        return $order;
    }

    public function updateDeliveredQuantity(Order $order): Order
    {
        if (! $order->wasChanged('status') || $order->status !== 5 || $order->delivered_quantity || $order->delivered_at) {
            return $order;
        }

        // Auto-populate delivered_quantity and delivered_at when order status changes to completed
        // Status 5 = completed, check if delivered_quantity is not already set
        // Recalculate delivered quantity from current cart items
        // (may have been modified during fulfillment by upselling/cross-selling)
        $deliveredQty = $this->getOrderedQuantity($order);
        $order->update([
            'delivered_quantity' => $deliveredQty,
            'delivered_at' => now(),
        ]);

        return $order;
    }

    /**
     * Get the total quantity of products in an order
     */
    public function getOrderedQuantity(Order $order): int
    {
        return (int) Cart::where('order_id', $order->id)
            ->sum('quantity');
    }

    /**
     * Product IDs currently present on the order (delivery-time set).
     *
     * @return array<int>
     */
    public function getDeliveredProductIds(Order $order): array
    {
        return Cart::where('order_id', $order->id)
            ->pluck('product_id')
            ->filter(fn ($id) => ! empty($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Product IDs captured at order creation time. Falls back for legacy rows.
     *
     * @return array<int>
     */
    public function getOrderedProductIdsSnapshot(Order $order): array
    {
        $snapshotIds = collect((array) ($order->ordered_product_ids ?? []))
            ->filter(fn ($id) => ! empty($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($snapshotIds->isNotEmpty()) {
            return $snapshotIds->all();
        }

        $fallbackIds = collect();

        if (! empty($order->product_id)) {
            $fallbackIds->push((int) $order->product_id);
        }

        $slugs = collect((array) ($order->product_slug ?? []))
            ->filter(fn ($slug) => is_string($slug) && trim($slug) !== '')
            ->map(fn ($slug) => trim((string) $slug))
            ->unique()
            ->values();

        if ($slugs->isNotEmpty()) {
            $slugIds = Product::query()
                ->whereIn('slug', $slugs->all())
                ->pluck('id')
                ->map(fn ($id) => (int) $id);

            $fallbackIds = $fallbackIds->merge($slugIds);
        }

        return $fallbackIds
            ->filter(fn ($id) => ! empty($id))
            ->unique()
            ->values()
            ->all();
    }
}
