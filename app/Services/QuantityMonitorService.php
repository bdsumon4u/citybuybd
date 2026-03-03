<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;

class QuantityMonitorService
{
    /**
     * Update ordered_quantity based on cart products assigned to the order.
     * This should be called after all cart items have been assigned to the order.
     */
    public function updateOrderedQuantity(Order $order): Order
    {
        // Sum all quantities from cart items associated with this order
        $totalQuantity = Cart::where('order_id', $order->id)
            ->sum('quantity');

        // Update the order with the calculated quantity
        $order->update(['ordered_quantity' => $totalQuantity]);

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
}
