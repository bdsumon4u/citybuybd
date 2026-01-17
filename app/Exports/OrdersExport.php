<?php

namespace App\Exports;

use App\Models\Cart;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
        // $orders= Order::with('cart','cart.product')

        // ->whereIn('id',explode(",",$this->selectedRows))
        // ->get();
        // dd($orders);

    }

    public function map($orders): array
    {
        $product_name = [];
        $product_sku = [];

        $quantity = 0;
        foreach ($orders->many_cart as $cart) {
            $product_name[] = $cart->product->name;
            $product_sku[] = $cart->product?->sku;
            $quantity += $cart->quantity;
        }
        $product_string = implode(',', $product_name);
        $sku_string = implode(',', $product_sku);

        return [
            $orders->created_at->format('d-m-Y'),
            'INV'.$orders->id,

            $orders->name,
            $orders->address,
            $orders->phone,
            $orders->total,
            $product_string,
            $quantity,
            $orders->shipping_method == 9 ? 'ঢাকার ভিতরে ' : 'ঢাকার বাইরে ',
            $sku_string,

        ];
    }

    public function headings(): array
    {
        return [
            'Order Date',
            'Order ID',
            'Name',
            'Address',
            'Number',
            'Total Amount',
            'Item Name',
            'Quantity',
            'Shipping Method',
            'SKU',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return $orders= Order::with('cart','cart.product')

        //        return $carts= Cart::with('order','product')
        //        ->whereIn('order_id',explode(",",$this->selectedRows))
        //        ->get();

        return $orders = Order::with('many_cart', 'many_cart.product', 'couriers', 'order_city', 'order_zone')
            ->whereIn('id', explode(',', $this->selectedRows))
            ->get();

        // ->whereIn('id',explode(",",$this->selectedRows))
        // ->get();

        // $orders= Order::whereIn('id',explode(",",$this->selectedRows))->get();
        // dd($orders);
    }
}
