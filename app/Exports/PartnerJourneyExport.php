<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartnerJourneyExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $partnerId;

    public function __construct(int $partnerId)
    {
        $this->partnerId = $partnerId;
    }

    public function query()
    {
        return Transaction::query()->where('partner_id', $this->partnerId)->with('product', 'consultants');
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Product Name',
            'Borrow Date',
            'Return Date',
            'Status',
            'Consultants'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_id,
            $transaction->product?->product_name,
            $transaction->borrow_date,
            $transaction->actual_return_date,
            $transaction->status,
            $transaction->consultants->pluck('name')->join(', ')
        ];
    }
}