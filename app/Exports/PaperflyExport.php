<?php

namespace App\Exports;

use App\Models\Cart;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaperflyExport implements FromCollection, WithHeadings, WithMapping
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
        foreach ($orders->many_cart as $cart) {
            $product_name[] = $cart->product->name;
        }
        $string = implode(',', $product_name);

        return [

            'M-1-0001',
            'test00123',
            'Merchant1',
            'testAddress',
            'Dhaka',
            'Adabor',
            '01688121410',
            'standard',
            'regular',

            $string,
            $orders->total,
            $orders->name,
            $orders->address,
            $orders->order_city?->city,
            $orders->order_zone?->zone,
            $orders->phone,
            $orders->total,
            $orders->total,

        ];
    }

    public function headings(): array
    {
        return [
            'Merchant Code',
            'Merchant Order Reference',
            'Pick-up Merchant Name',
            'Pick-up Merchant Address',
            'District Name',
            'Thana Name',
            'Pick-up Merchant Phone',
            'Package Option',
            'Delivery Option',
            'Product Brief',
            'Package Price',
            'Customer Name',
            'Customer Address',
            'Customer District Name',
            'Customer Thana Name',
            'Customer Phone',
            'Actual Price',
            'Delivery Charge',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return $orders= Order::with('cart','cart.product')

        return $orders = Order::with('many_cart', 'many_cart.product')
            ->whereIn('id', explode(',', $this->selectedRows))
            ->get();

        //        return $carts= Cart::with('order','product')
        //        ->whereIn('order_id',explode(",",$this->selectedRows))
        //        ->get();

        // ->whereIn('id',explode(",",$this->selectedRows))
        // ->get();

        // $orders= Order::whereIn('id',explode(",",$this->selectedRows))->get();
        // dd($orders);
    }
}
