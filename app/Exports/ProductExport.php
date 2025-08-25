<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $type;
    protected $attributeHeadings = [];
    protected $conditionHeadings = [];

    public function __construct(string $type)
    {
        $this->type = $type;

        $projectId = session('current_project_id');
        if ($projectId) {
            $project = Project::with('productAttributes', 'conditionDefinitions')->find($projectId);
            if ($project) {
                $this->attributeHeadings = $project->productAttributes->pluck('name')->toArray();
                $this->conditionHeadings = $project->conditionDefinitions->pluck('name')->toArray();
            }
        }
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
        $baseHeadings = ["Product ID", "Product Name", "Key Identifier", "Status", "Is Active?"];
        return array_merge($baseHeadings, $this->attributeHeadings, $this->conditionHeadings);
    }

    public function map($product): array
    {
        $baseData = [
            $product->product_id,
            $product->product_name,
            $product->key_attribute_value,
            $product->status,
            $product->is_active ? 'Yes' : 'No',
        ];

        $attributeData = [];
        foreach ($this->attributeHeadings as $attributeName) {
            $attributeData[] = $product->attributes[$attributeName] ?? 'N/A';
        }

        $conditionData = [];
        foreach ($this->conditionHeadings as $conditionName) {
            $conditionData[] = $product->conditions[$conditionName] ?? 'N/A';
        }

        return array_merge($baseData, $attributeData, $conditionData);
    }
}
