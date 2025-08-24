<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
    * Menentukan query berdasarkan tipe yang dipilih.
    */
    public function query()
    {
        $query = Transaction::query()->with('product', 'partner', 'consultants', 'project.conditionDefinitions');

        if ($this->type === 'active') {
            $query->whereIn('status', ['Active', 'Awaiting Receipt Upload']);
        } elseif ($this->type === 'history') {
            $query->whereIn('status', ['Completed', 'Completed (Unreturned)']);
        }
        // Jika 'all', tidak ada filter status

        return $query->orderBy('borrow_date', 'desc');
    }

    /**
     * Menentukan header untuk kolom Excel.
     */
    public function headings(): array
    {
        return [
            'Transaction ID', 'Product', 'Key Identifier', 'Partner', 'Consultants',
            'Borrow Date', 'Est. Return Date', 'Actual Return Date', 'Status',
            'Loan Receipt Status', 'Return Receipt Status', 'Notes',
            'Return Conditions', 'News Links' // <-- KOLOM BARU
        ];
    }

    /**
     * Memetakan setiap baris data ke format array untuk Excel.
     * @param Transaction $transaction
     */
    public function map($transaction): array
    {
        // Format kolom Return Conditions
        $conditionsText = '';
        if ($transaction->return_conditions && is_array($transaction->return_conditions)) {
            $conditions = [];
            foreach ($transaction->return_conditions as $key => $value) {
                // Cari nama kondisi berdasarkan ID-nya
                $conditionName = $transaction->project?->conditionDefinitions?->find($key)?->name ?? 'Condition ' . $key;
                $conditions[] = "{$conditionName}: {$value}";
            }
            $conditionsText = implode('; ', $conditions);
        }

        // Format kolom News Links
        $newsText = '';
        $newsLinks = json_decode($transaction->news_links);
        if (is_array($newsLinks) && !empty($newsLinks)) {
            $links = [];
            foreach ($newsLinks as $link) {
                if (isset($link->title) && isset($link->link)) {
                    $links[] = "{$link->title} ({$link->link})";
                }
            }
            $newsText = implode('; ', $links);
        }

        return [
            $transaction->transaction_id,
            $transaction->product?->product_name,
            $transaction->product?->key_attribute_value,
            $transaction->partner?->partner_name,
            $transaction->consultants->pluck('name')->join(', '),
            $transaction->borrow_date,
            $transaction->estimated_return_date,
            $transaction->actual_return_date,
            $transaction->status,
            $transaction->loan_receipt_status,
            $transaction->return_receipt_status,
            $transaction->notes,
            $conditionsText, // <-- DATA BARU
            $newsText,       // <-- DATA BARU
        ];
    }
}
