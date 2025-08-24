<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PublicUploadController;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- RUTE PUBLIK (TIDAK PERLU LOGIN) ---
Route::get('/upload/loan/{token}', [PublicUploadController::class, 'showLoanUploadForm'])->name('public.loan.upload.form');
Route::post('/upload/loan/{token}', [PublicUploadController::class, 'handleLoanUpload'])->name('public.loan.upload.handle');
Route::get('/upload/return/{token}', [PublicUploadController::class, 'showReturnUploadForm'])->name('public.return.upload.form');
Route::post('/upload/return/{token}', [PublicUploadController::class, 'handleReturnUpload'])->name('public.return.upload.handle');


// --- RUTE YANG MEMBUTUHKAN LOGIN TAPI BELUM MEMILIH PROYEK ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/project-select', [ProjectController::class, 'showSelection'])->name('projects.select');
    Route::post('/project-select/{project}', [ProjectController::class, 'selectProject'])->name('projects.select.submit');
    
    Route::view('/about', 'about')->name('about');
    Route::view('/how-to-use', 'how-to-use')->name('how-to-use');
});

// --- RUTE AREA UTAMA APLIKASI (SEMUA USER BISA AKSES SETELAH PILIH PROYEK) ---
Route::middleware(['auth', 'project.selected'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [TransactionController::class, 'history'])->name('history.index');

    // Rute spesifik harus di atas rute resource
    Route::patch('/products/{product}/status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::patch('/partners/{partner}/status', [PartnerController::class, 'toggleStatus'])->name('partners.toggleStatus');
    Route::get('/products/{product}/journey', [ProductController::class, 'journey'])->name('products.journey');
    Route::get('/products/{product}/journey/export', [ProductController::class, 'exportJourney'])->name('products.journey.export');
    Route::get('/partners/{partner}/journey', [PartnerController::class, 'journey'])->name('partners.journey');
    Route::get('/partners/{partner}/journey/export', [PartnerController::class, 'exportJourney'])->name('partners.journey.export');
    Route::get('/transactions/{transaction}/return', [TransactionController::class, 'showReturnForm'])->name('transactions.return');
    Route::post('/transactions/{transaction}/return', [TransactionController::class, 'processReturn'])->name('transactions.processReturn');
    Route::get('/transactions/{transaction}/loan-receipt/download', [TransactionController::class, 'downloadLoanReceipt'])->name('transactions.loan.receipt');
    Route::post('/transactions/{transaction}/loan-receipt/send', [TransactionController::class, 'sendLoanReceipt'])->name('transactions.loan.receipt.send');
    Route::get('/transactions/{transaction}/return-receipt/download', [TransactionController::class, 'downloadReturnReceipt'])->name('transactions.return.receipt');
    Route::post('/transactions/{transaction}/return-receipt/send', [TransactionController::class, 'sendReturnReceipt'])->name('transactions.return.receipt.send');
    Route::get('/transactions/{transaction}/news', [TransactionController::class, 'showNews'])->name('transactions.news');
    Route::get('/transactions/{transaction}/news/export', [TransactionController::class, 'exportNews'])->name('transactions.news.export');
    Route::get('/transactions/{transaction}/details-for-email', [TransactionController::class, 'getDetailsForEmail'])->name('transactions.detailsForEmail');

    // Rute resource
    Route::resource('products', ProductController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('transactions', TransactionController::class);
    
    // Rute untuk Dropdown Pencarian & Ekspor
    Route::get('/select/products', [SelectController::class, 'products'])->name('select.products');
    Route::get('/select/partners', [SelectController::class, 'partners'])->name('select.partners');
    Route::get('/select/users', [SelectController::class, 'users'])->name('select.users');
    Route::get('/export/requests', [ExportController::class, 'showRequestExportForm'])->name('export.requests.form');
    Route::post('/export/requests', [ExportController::class, 'handleRequestExport'])->name('export.requests.handle');
    Route::post('/export/products', [ExportController::class, 'handleProductExport'])->name('export.products.handle');
    Route::post('/export/partners', [ExportController::class, 'handlePartnerExport'])->name('export.partners.handle');
});

// --- RUTE KHUSUS ADMIN (WAJIB LOGIN & ROLE ADMIN) ---
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('projects', ProjectController::class);
});

require __DIR__.'/auth.php';
