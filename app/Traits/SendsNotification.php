<?php

namespace App\Traits;

use App\Models\Cart;
use App\Models\Order;

trait SendsNotification
{
    public static function getTemplateVariables(Order $order): array
    {
        return match($order->status) {
            Order::STATUS_PROCESSING => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
            ],
            Order::STATUS_PENDING_DELIVERY => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
            ],
            Order::STATUS_ON_HOLD => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
            ],
            Order::STATUS_CANCEL => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
            ],
            Order::STATUS_COMPLETED => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
                'Tracking Link' => $order->getTrackingLinkVariable(),
            ],
            Order::STATUS_PENDING_PAYMENT => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
            ],
            Order::STATUS_ON_DELIVERY => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
                'Tracking Link' => $order->getTrackingLinkVariable(),
            ],
            Order::STATUS_NO_RESPONSE1 => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
            ],
            Order::STATUS_NO_RESPONSE2 => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
            ],
            Order::STATUS_COURIER_HOLD => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
                'Tracking Link' => $order->getTrackingLinkVariable(),
            ],
            Order::STATUS_ORDER_RETURN => [
                'Name' => $order->getNameVariable(),
                'Invoice ID' => $order->getInvoiceIdVariable(),
                'Product Details' => $order->getProductDetailsVariable(),
                'Product Price' => $order->getProductPriceVariable(),
                'Delivery Charge' => $order->getDeliveryChargeVariable(),
                'Total Amount' => $order->getTotalAmountVariable(),
            ],
            default => [],
        };
    }

    public function getNameVariable(): string
    {
        return $this->name ?? 'Customer';
    }

    public function getInvoiceIdVariable(): string
    {
        return $this->id ?? 'N/A';
    }

    public function getProductDetailsVariable(): string
    {
        if (!$this->products || $this->products->isEmpty()) {
            return 'N/A';
        }

        return $this->products->map(function (Cart $cart) {
            return '- '.$cart->product->name . ' [Q:' . $cart->quantity . ']';
        })->implode('\n') ?? 'N/A';
    }

    public function getProductPriceVariable(): string
    {
        if (!$this->products || $this->products->isEmpty()) {
            return 'N/A';
        }

        return $this->products->implode('price', ', ') ?? 'N/A';
    }

    public function getDeliveryChargeVariable(): string
    {
        return $this->shipping_cost ?? 'N/A';
    }

    public function getTotalAmountVariable(): string
    {
        return (string) ($this->total ?? 'N/A');
    }

    public function getTrackingLinkVariable(): string
    {
        if ($this->courier == 1 && $this->consignment_id) {//1 = RedX
            return 'https://redx.com.bd/track-global-parcel/?trackingId=' . $this->consignment_id;
        } elseif ($this->courier == 3 && $this->consignment_id) {//3 = pathao
            return 'https://merchant.pathao.com/tracking?consignment_id=' . $this->consignment_id . '&phone=' . $this->phone;
        } elseif ($this->courier == 4 && $this->consignment_id) {//4 = steadfast
            return 'https://steadfast.com.bd/t/' . $this->consignment_id;
        }

        return "N/A";
    }
}
