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

    public function getNameVariable(): ?string
    {
        return $this->name;
    }

    public function getInvoiceIdVariable(): ?int
    {
        return $this->id;
    }

    public function getProductDetailsVariable(): ?string
    {
        return $this->products->map(function (Cart $cart) {
            return '- '.$cart->product->name . ' [Q:' . $cart->quantity . ']';
        })->implode('\n');
    }

    public function getProductPriceVariable(): ?string
    {
        return $this->products->implode('price', ', ');
    }

    public function getDeliveryChargeVariable(): ?float
    {
        return $this->shipping_cost;
    }

    public function getTotalAmountVariable(): ?float
    {
        return $this->total;
    }

    public function getTrackingLinkVariable(): string
    {
        return $this->getMyCourierAttribute();
    }
}
