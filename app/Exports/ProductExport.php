<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function query()
    {
        $query = Product::query();

        if ($this->type === 'active') {
            $query->where('is_active', true);
        }
        // Jika 'all', tidak ada filter tambahan

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return ["Product ID", "Product Name", "Key Identifier", "Status", "Is Active?"];
    }

    public function map($product): array
    {
        return [
            $product->product_id,
            $product->product_name,
            $product->key_attribute_value,
            $product->status,
            $product->is_active ? 'Yes' : 'No',
        ];
    }
}