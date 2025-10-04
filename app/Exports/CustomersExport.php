<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CustomersExport implements FromCollection,WithHeadings
{   
    public function headings():array{
        return[
            'name','address','phone'
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return AppModelsProduct::all();
        return collect(Order::getCustomers());
    }
}
