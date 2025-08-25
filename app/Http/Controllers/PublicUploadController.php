<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PublicUploadController extends Controller
{
    public function showLoanUploadForm(string $token)
    {
        $transaction = Transaction::where('loan_upload_token', $token)->firstOrFail();
        return view('public.upload-loan', compact('transaction'));
    }

    public function handleLoanUpload(Request $request, string $token)
    {
        $transaction = Transaction::where('loan_upload_token', $token)->firstOrFail();

        $request->validate([
            'signed_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('signed_receipt')->store('signed_receipts/loan', 'public');

        $updateData = [
            'loan_receipt_status' => 'Uploaded',
            'loan_receipt_path' => $path,
        ];

        // --- LOGIKA BARU UNTUK UNRETURNED ITEM ---
        if ($transaction->is_unreturned) {
            // Langsung ubah status transaksi menjadi 'Completed (Unreturned)'
            $updateData['status'] = 'Completed (Unreturned)';

            // Update juga status produk menjadi 'Unreturned' agar tidak bisa dipinjam lagi
            $transaction->product->update(['status' => 'Unreturned']);
        }
        // ------------------------------------------

        $transaction->update($updateData);

        return view('public.upload-success');
    }

    /**
     * Menampilkan form untuk upload Tanda Terima Pengembalian.
     */
    public function showReturnUploadForm(string $token)
    {
        $transaction = \App\Models\Transaction::where('return_upload_token', $token)->firstOrFail();
        return view('public.upload-return', compact('transaction'));
    }

    public function handleReturnUpload(Request $request, string $token)
    {
        $transaction = \App\Models\Transaction::where('return_upload_token', $token)->firstOrFail();

        $request->validate([
            'signed_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('signed_receipt')->store('signed_receipts/return', 'public');

        // Gunakan DB Transaction untuk memastikan semua update berhasil
        \Illuminate\Support\Facades\DB::transaction(function () use ($transaction, $path) {
            // 1. Update status transaksi menjadi final
            $transaction->update([
                'return_receipt_status' => 'Uploaded',
                'return_receipt_path' => $path,
                'status' => 'Completed',
            ]);
    
            // 2. Update data produk dengan status Available dan kondisi terbaru
            $transaction->product->update([
                'status' => 'Available',
                'current_transaction_id' => null,
                'conditions' => $transaction->return_conditions, // <-- INI LOGIKA PENTINGNYA
            ]);
        });

        return view('public.upload-success');
    }
}
