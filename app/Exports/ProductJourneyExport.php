<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductJourneyExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    /**
    * Menentukan query dasar untuk mengambil data.
    */
    public function query()
    {
        return Transaction::query()
            ->where('product_id', $this->productId)
            ->with('partner', 'project.conditionDefinitions') // Eager load relasi
            ->orderBy('borrow_date', 'desc');
    }

    /**
     * Menentukan header untuk kolom Excel.
     */
    public function headings(): array
    {
        return [
            'Transaction ID',
            'Partner',
            'PIC Name',
            'Borrow Date',
            'Return Date',
            'Status',
            'Return Conditions',
            'News Links'
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
                $conditionName = $transaction->project?->conditionDefinitions?->find($key)?->name ?? 'Unknown';
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
                $links[] = "{$link->title} ({$link->link})";
            }
            $newsText = implode('; ', $links);
        }

        return [
            $transaction->transaction_id,
            $transaction->partner?->partner_name,
            $transaction->partner?->pic_name,
            $transaction->borrow_date,
            $transaction->actual_return_date,
            $transaction->status,
            $conditionsText,
            $newsText,
        ];
    }
}