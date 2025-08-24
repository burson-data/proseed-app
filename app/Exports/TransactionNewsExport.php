<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionNewsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengubah data JSON menjadi collection agar bisa di-loop
        return collect(json_decode($this->transaction->news_links));
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Ini akan menjadi header kolom di file Excel
        return [
            'Title',
            'URL',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // Ini mengatur data apa yang akan dimasukkan ke setiap baris
        return [
            $row->title,
            $row->link,
        ];
    }
}