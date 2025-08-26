<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Imports\PartnersImport;
use App\Exports\TransactionExport;
use App\Exports\ProductExport;
use App\Exports\PartnerExport;

class DataManagementController extends Controller
{
    /**
     * Display the data management view.
     */
    public function index()
    {
        return view('data-management.index');
    }

    // --- IMPORT METHODS ---

    public function importProducts(Request $request)
    {
        $request->validate([
            'product_file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('product_file'));
            return back()->with('status', 'Product import successful!');
        } catch (\Exception $e) {
            return back()->withErrors(['product_file' => 'Import failed! Please check your file. Error: ' . $e->getMessage()]);
        }
    }

    public function importPartners(Request $request)
    {
        $request->validate([
            'partner_file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new PartnersImport, $request->file('partner_file'));
            return back()->with('status', 'Partner import successful!');
        } catch (\Exception $e) {
            return back()->withErrors(['partner_file' => 'Import failed! Please check your file. Error: ' . $e->getMessage()]);
        }
    }

    // --- EXPORT METHODS ---

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
