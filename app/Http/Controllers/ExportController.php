<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;   // <-- Tambahkan ini
use App\Exports\PartnerExport; 

class ExportController extends Controller
{
    /**
     * Menampilkan halaman form pilihan ekspor.
     */
    public function showRequestExportForm()
    {
        return view('export.requests');
    }

    /**
     * Menangani permintaan ekspor dan memulai download.
     */
    public function handleRequestExport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:active,history,all',
        ]);

        $type = $request->input('type');
        $fileName = 'proseed_requests_' . $type . '_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new TransactionExport($type), $fileName);
    }

    public function handleProductExport(Request $request)
    {
        $request->validate(['type' => 'required|in:all,active']);
        $type = $request->input('type');
        $fileName = 'proseed_products_' . $type . '_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ProductExport($type), $fileName);
    }

    public function handlePartnerExport(Request $request)
    {
        $request->validate(['type' => 'required|in:all,active']);
        $type = $request->input('type');
        $fileName = 'proseed_partners_' . $type . '_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new PartnerExport($type), $fileName);
    }
}