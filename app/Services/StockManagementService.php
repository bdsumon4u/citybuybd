<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Controllers\Backend\ReportController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockManagementService
{
    /**
     * Deduct stock for all items in an order when it moves to On Delivery.
     * Ensures idempotency: will not deduct more than once for the same order.
     */
    public function deductOrderStock(Order $order): void
    {
        // Check if stock for this order has already been deducted
        $alreadyDeducted = StockLog::where('order_id', $order->id)
            ->where('type', StockLog::TYPE_ORDER_DELIVERY)
            ->exists();

        if ($alreadyDeducted) {
            return;
        }

        $order->loadMissing(['many_cart.product']);

        if ($order->many_cart->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($order): void {
            foreach ($order->many_cart as $cart) {
                $product = $cart->product;
                if (! $product) {
                    continue;
                }

                // Resolve the base product (if combo, returns referenced base product; if base, returns itself)
                $baseProduct = $product->getEffectiveBaseProduct();
                if (! $baseProduct) {
                    continue;
                }

                // Lock base product row for update
                $baseProduct = Product::where('id', $baseProduct->id)->lockForUpdate()->first();
                if (! $baseProduct) {
                    continue;
                }

                $packMultiplier = ReportController::extractPackMultiplier($cart->package, $cart->size, $product->bulk_prices);
                $baseMultiplier = $product->getEffectiveMultiplier();
                $cartQty = max(1, (int) $cart->quantity);

                // Total units to deduct from base product stock
                $totalUnitsToDeduct = $cartQty * $packMultiplier * $baseMultiplier;

                if ($totalUnitsToDeduct <= 0) {
                    continue;
                }

                $stockBefore = (int) ($baseProduct->stock ?? 0);
                $stockAfter = $stockBefore - $totalUnitsToDeduct;

                // Update base product stock
                $baseProduct->stock = (string) $stockAfter;
                $baseProduct->save();

                // Create audit stock log
                StockLog::create([
                    'product_id' => $baseProduct->id,
                    'order_id' => $order->id,
                    'cart_id' => $cart->id,
                    'type' => StockLog::TYPE_ORDER_DELIVERY,
                    'quantity' => -$totalUnitsToDeduct,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'notes' => "Order #{$order->id} on delivery: {$cartQty}x {$product->name} (Pack: {$packMultiplier}, Multiplier: {$baseMultiplier}) => -{$totalUnitsToDeduct} units",
                    'created_by' => auth()->id() ?? $order->created_by ?? null,
                ]);

                Log::info("Stock deducted for Order #{$order->id}: Base Product #{$baseProduct->id} ({$baseProduct->name}) reduced by {$totalUnitsToDeduct} units. New stock: {$stockAfter}");
            }
        });
    }

    /**
     * Restore stock for an order when returned from courier or cancelled after being on delivery.
     * Ensures idempotency: only restores units that were actually deducted and not yet restored.
     */
    public function restoreOrderStock(Order $order, ?int $newStatus = null): void
    {
        $deliveryLogs = StockLog::where('order_id', $order->id)
            ->where('type', StockLog::TYPE_ORDER_DELIVERY)
            ->get();

        if ($deliveryLogs->isEmpty()) {
            return;
        }

        // Check if already restored
        $alreadyRestoredCount = StockLog::where('order_id', $order->id)
            ->where('type', StockLog::TYPE_ORDER_RETURN)
            ->count();

        if ($alreadyRestoredCount >= $deliveryLogs->count()) {
            return;
        }

        DB::transaction(function () use ($order, $deliveryLogs): void {
            $restoreType = StockLog::TYPE_ORDER_RETURN;

            foreach ($deliveryLogs as $deliveryLog) {
                // Check if this specific cart/delivery log has already been restored
                $alreadyRestored = StockLog::where('order_id', $order->id)
                    ->where('cart_id', $deliveryLog->cart_id)
                    ->where('type', StockLog::TYPE_ORDER_RETURN)
                    ->exists();

                if ($alreadyRestored) {
                    continue;
                }

                $baseProduct = Product::where('id', $deliveryLog->product_id)->lockForUpdate()->first();
                if (! $baseProduct) {
                    continue;
                }

                $unitsToRestore = abs((int) $deliveryLog->quantity);
                if ($unitsToRestore <= 0) {
                    continue;
                }

                $stockBefore = (int) ($baseProduct->stock ?? 0);
                $stockAfter = $stockBefore + $unitsToRestore;

                $baseProduct->stock = (string) $stockAfter;
                $baseProduct->save();

                StockLog::create([
                    'product_id' => $baseProduct->id,
                    'order_id' => $order->id,
                    'cart_id' => $deliveryLog->cart_id,
                    'type' => $restoreType,
                    'quantity' => $unitsToRestore,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'notes' => "Order #{$order->id} return/restoration: Restored +{$unitsToRestore} units for Base Product {$baseProduct->name}",
                    'created_by' => auth()->id() ?? null,
                ]);

                Log::info("Stock restored for Order #{$order->id}: Base Product #{$baseProduct->id} ({$baseProduct->name}) restored +{$unitsToRestore} units. New stock: {$stockAfter}");
            }
        });
    }

    /**
     * Record a purchase/stock intake for a base product.
     */
    public function recordPurchase(array $data, ?int $userId = null): Purchase
    {
        return DB::transaction(function () use ($data, $userId): Purchase {
            $product = Product::where('id', $data['product_id'])->lockForUpdate()->firstOrFail();

            if ($product->isCombo()) {
                throw new \InvalidArgumentException('Stock purchases can only be added to base products.');
            }

            $quantity = max(1, (int) $data['quantity']);
            $unitPrice = isset($data['unit_price']) && $data['unit_price'] !== '' && $data['unit_price'] !== null ? (float) $data['unit_price'] : null;
            $totalPrice = isset($data['total_price']) && $data['total_price'] !== '' && $data['total_price'] !== null ? (float) $data['total_price'] : ($unitPrice !== null ? $unitPrice * $quantity : null);

            $purchase = Purchase::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'supplier' => ! empty($data['supplier']) ? trim((string) $data['supplier']) : null,
                'invoice_no' => ! empty($data['invoice_no']) ? trim((string) $data['invoice_no']) : null,
                'purchase_date' => ! empty($data['purchase_date']) ? $data['purchase_date'] : now()->toDateString(),
                'notes' => ! empty($data['notes']) ? trim((string) $data['notes']) : null,
                'created_by' => $userId ?? auth()->id(),
            ]);

            $stockBefore = (int) ($product->stock ?? 0);
            $stockAfter = $stockBefore + $quantity;

            $product->stock = (string) $stockAfter;
            $product->save();

            StockLog::create([
                'product_id' => $product->id,
                'purchase_id' => $purchase->id,
                'type' => StockLog::TYPE_PURCHASE,
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => 'Stock Purchase: +'.$quantity.' units'.($purchase->supplier ? " (Supplier: {$purchase->supplier})" : '').($purchase->invoice_no ? " (Inv: {$purchase->invoice_no})" : ''),
                'created_by' => $userId ?? auth()->id(),
            ]);

            return $purchase;
        });
    }

    /**
     * Manual stock adjustment / count reconciliation.
     */
    public function adjustStock(Product $product, int $newStock, ?string $reason = null, ?int $userId = null): void
    {
        DB::transaction(function () use ($product, $newStock, $reason, $userId): void {
            $lockedProduct = Product::where('id', $product->id)->lockForUpdate()->firstOrFail();

            $stockBefore = (int) ($lockedProduct->stock ?? 0);
            $difference = $newStock - $stockBefore;

            if ($difference === 0) {
                return;
            }

            $lockedProduct->stock = (string) $newStock;
            $lockedProduct->save();

            StockLog::create([
                'product_id' => $lockedProduct->id,
                'type' => StockLog::TYPE_MANUAL_ADJUSTMENT,
                'quantity' => $difference,
                'stock_before' => $stockBefore,
                'stock_after' => $newStock,
                'notes' => 'Manual Stock Adjustment: '.($difference > 0 ? "+{$difference}" : "{$difference}").' units'.($reason ? " (Reason: {$reason})" : ''),
                'created_by' => $userId ?? auth()->id(),
            ]);
        });
    }
}
