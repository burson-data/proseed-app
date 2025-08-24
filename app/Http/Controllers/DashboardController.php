<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data statistik untuk proyek yang aktif.
     */
    public function index()
    {
        $currentProjectId = session('current_project_id');
        if (!$currentProjectId) {
            return redirect()->route('projects.select');
        }

        // --- Data Statistik (Tetap Sama) ---
        $projectName = \App\Models\Project::findOrFail($currentProjectId)->name;
        $activeProducts = Product::where('is_active', true)->count();
        $borrowedProducts = \App\Models\Product::where('status', 'Borrowed')->count();
        $totalTransactions = \App\Models\Transaction::count();
        $lateItems = \App\Models\Transaction::where('status', 'Active')
                                ->where('estimated_return_date', '<', now()->toDateString())
                                ->count();

        // --- DATA BARU UNTUK DASHBOARD ---
        
        // 1. Ambil 5 transaksi terbaru (aktivitas terakhir)
        $recentTransactions = \App\Models\Transaction::with('product', 'partner')
                                ->latest() // Mengurutkan berdasarkan created_at (terbaru dulu)
                                ->take(5) // Ambil 5 teratas
                                ->get();

        // 2. Ambil item yang harus dikembalikan dalam 7 hari ke depan
        $dueSoonTransactions = \App\Models\Transaction::with('product', 'partner')
                                    ->where('status', 'Active')
                                    ->whereBetween('estimated_return_date', [now(), now()->addDays(7)])
                                    ->orderBy('estimated_return_date', 'asc')
                                    ->get();

        // Kirim semua data ke view
        return view('dashboard', [
            'projectName' => $projectName,
            'activeProducts' => $activeProducts,
            'borrowedProducts' => $borrowedProducts,
            'totalTransactions' => $totalTransactions,
            'lateItems' => $lateItems,
            'recentTransactions' => $recentTransactions, // Data baru
            'dueSoonTransactions' => $dueSoonTransactions, // Data baru
        ]);
    }
}