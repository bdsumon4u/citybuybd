<?php

namespace App\Exports;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Settings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RedxExport implements FromCollection, WithHeadings, WithMapping
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

        return [
            //             $carts->created_at->format('d-m-Y'),

            //             'INV'.$carts->order->id,
            $orders->id,
            $orders->name,
            $orders->phone,
            $orders->address,
            $orders->order_city?->city,
            $orders->order_zone?->zone,
            '',
            '',
            $orders->total,
            $orders->total,
            '.5',
            '',
            $settings->insta_link,
            $settings->phone,

        ];
    }

    public function headings(): array
    {
        return [

            'Invoice',
            'Customer Name',
            'Contact No',
            'Customer Address',
            'District',
            'Area',
            'Area ID',
            'Division',
            'Price',
            'Product Selling Price',
            'Weight(g)',
            'Instruction',
            'Seller Name',
            'Seller Phone',

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
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
