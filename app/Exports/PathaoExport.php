<?php

namespace App\Exports;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Settings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PathaoExport implements FromCollection, WithHeadings, WithMapping
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
        $settings = Settings::find(1);
        $product_name = [];
        $quantity = 0;
        foreach ($orders->many_cart as $cart) {
            $product_name[] = $cart->product->name;
            $quantity += $cart->quantity;
        }
        $string = implode(',', $product_name);

        return [
            'parcel',
            $settings->insta_link,
            $orders->id,
            $orders->name,
            $orders->phone,
            $orders->order_city?->city,
            $orders->order_zone?->zone,
            '',
            $orders->address,
            $orders->total,
            $quantity,
            '.5',
            $string,
            '',

        ];
    }

    public function headings(): array
    {
        return [
            'ItemType(*)',
            'StoreName(*)',
            'MerchantOrderId',
            'RecipientName(*)',
            'RecipientPhone(*)',
            'RecipientCity(*)',
            'RecipientZone(*)',
            'RecipientArea',
            'RecipientAddress(*)',
            'AmountToCollect(*)',
            'ItemQuantity(*)',
            'ItemWeight(*)',
            'ItemDesc',
            'SpecialInstruction',

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return $orders= Order::with('cart','cart.product')
        return $orders = Order::with('many_cart', 'many_cart.product', 'couriers')
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
