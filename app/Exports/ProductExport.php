<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id', 'sku', 'thumb', 'image', 'gallery_images', 'name', 'slug', 'stock', 'description', 'category_id', 'color', 'size', 'regular_price', 'offer_price', 'status', 'created_at',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return AppModelsProduct::all();
        return collect(Product::getProduct());
    }
}
