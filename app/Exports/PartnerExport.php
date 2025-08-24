<?php

namespace App\Exports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartnerExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function query()
    {
        $query = Partner::query();

        if ($this->type === 'active') {
            $query->where('is_active', true);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return ["Partner ID", "Partner Name", "PIC Name", "Email", "Phone", "Is Active?"];
    }

    public function map($partner): array
    {
        return [
            $partner->partner_id,
            $partner->partner_name,
            $partner->pic_name,
            $partner->email,
            $partner->phone_number,
            $partner->is_active ? 'Yes' : 'No',
        ];
    }
}