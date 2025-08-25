<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\LoanReceiptMail;
use App\Mail\ReturnReceiptMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionNewsExport;



class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 15);
        $sortBy = $request->query('sort_by', 'borrow_date');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = Transaction::with('product', 'partner', 'consultants')
                            ->whereIn('status', ['Active', 'Awaiting Receipt Upload']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhereHas('product', function($subq) use ($search) {
                      $subq->where('product_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('partner', function($subq) use ($search) {
                      $subq->where('partner_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $transactions = $query->paginate($perPage)->appends($request->query());

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', 'Available')->get();
        $partners = Partner::where('is_active', true)->get();
        $consultants = User::all();

        return view('transactions.create', compact('products', 'partners', 'consultants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'partner_id' => 'required|exists:partners,id',
            'consultants' => 'nullable|array',
            'consultants.*' => 'exists:users,id',
            'estimated_return_date' => 'required|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'is_unreturned' => 'nullable|boolean',
        ]);

        $product = Product::findOrFail($request->product_id);
        $currentProjectId = session('current_project_id');

        if ($product->status !== 'Available') {
            return redirect()->back()
                ->with('error', 'Product is not available for loan.')
                ->withInput();
        }

        $transaction = DB::transaction(function () use ($request, $product, $validatedData, $currentProjectId) {
            $transactionCountInProject = Transaction::where('project_id', $currentProjectId)->count();
            $nextNum = $transactionCountInProject + 1;
            $transactionId = 'TRA-' . $currentProjectId . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

            $dataToCreate = [
                'project_id' => $currentProjectId,
                'transaction_id' => $transactionId,
                'product_id' => $validatedData['product_id'],
                'partner_id' => $validatedData['partner_id'],
                'borrow_date' => Carbon::now(),
                'estimated_return_date' => $validatedData['estimated_return_date'],
                'status' => 'Active',
                'purpose' => $validatedData['purpose'],
                'notes' => $validatedData['notes'],
                'is_unreturned' => $request->has('is_unreturned'),
            ];
            
            unset($dataToCreate['consultants']);

            $transaction = Transaction::create($dataToCreate);
            
            if ($request->has('consultants')) {
                $transaction->consultants()->attach($request->consultants);
            }
            
            $transaction->product->update([
                'status' => 'Borrowed',
                'current_transaction_id' => $transaction->transaction_id,
            ]);

            return $transaction;
        });

        return redirect()->route('transactions.index')
                         ->with('success', 'New request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('product', 'partner', 'consultants', 'project.conditionDefinitions');
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        if ($transaction->status !== 'Active') {
            return redirect()->route('transactions.index')->with('error', 'Only active transactions can be edited.');
        }

        $consultants = User::all();

        return view('transactions.edit', compact('transaction', 'consultants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'Active') {
            return redirect()->route('transactions.index')->with('error', 'Only active transactions can be edited.');
        }

        $validatedData = $request->validate([
            'consultants' => 'nullable|array',
            'consultants.*' => 'exists:users,id',
            'estimated_return_date' => 'required|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $transaction->update($validatedData);
        $transaction->consultants()->sync($request->consultants ?? []);

        return redirect()->route('transactions.index')
                         ->with('success', 'Request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Logic for deleting a transaction if needed in the future
    }

    /**
     * Show the form for processing a product return.
     */
    public function showReturnForm(Transaction $transaction)
    {
        if ($transaction->status !== 'Active') {
            return redirect()->route('transactions.index')->with('error', 'This transaction cannot be processed.');
        }
        
        $conditionDefinitions = $transaction->project->conditionDefinitions;

        return view('transactions.return', compact('transaction', 'conditionDefinitions'));
    }

    /**
     * Process the return of a product for a specific transaction.
     */
    public function processReturn(Request $request, Transaction $transaction)
    {
        $request->validate([
            'actual_return_date' => 'required|date',
            'return_conditions' => 'nullable|array',
        ]);

        $newsLinks = [];
        if ($request->has('news_title') && $request->has('news_link')) {
            foreach ($request->news_title as $index => $title) {
                if (!empty($title) && !empty($request->news_link[$index])) {
                    $newsLinks[] = [
                        'title' => $title,
                        'link' => $request->news_link[$index],
                    ];
                }
            }
        }

        DB::transaction(function () use ($request, $transaction, $newsLinks) {
            $transaction->update([
                'status' => 'Awaiting Receipt Upload',
                'actual_return_date' => $request->actual_return_date,
                'return_notes' => $request->return_notes,
                'news_links' => json_encode($newsLinks),
                'return_conditions' => $request->return_conditions ?? [],
            ]);
        });

        return redirect()->route('transactions.index')
                         ->with('success', 'Return is being processed. Waiting for signed receipt upload.');
    }

    /**
     * Get transaction details formatted for the email modal.
     */
    public function getDetailsForEmail(Transaction $transaction)
    {
        $transaction->load('product', 'partner');

        // Pastikan token ada sebelum membuat route. Jika belum ada, buat dan simpan.
        if (empty($transaction->loan_upload_token)) {
            $transaction->loan_upload_token = \Illuminate\Support\Str::uuid();
        }
        if (empty($transaction->return_upload_token)) {
            $transaction->return_upload_token = \Illuminate\Support\Str::uuid();
        }
        
        // Simpan hanya jika ada token baru yang dibuat
        if ($transaction->isDirty('loan_upload_token') || $transaction->isDirty('return_upload_token')) {
            $transaction->save();
        }

        // Template untuk Loan Receipt
        $loanSubject = "Tanda Terima : {$transaction->product?->product_name} untuk {$transaction->partner?->partner_name}";
        $loanUploadLink = route('public.loan.upload.form', $transaction->loan_upload_token);
        $loanBody = "<p>Halo,</p>\n" .
                    "<p>Terlampir adalah Tanda Terima untuk produk <strong>{$transaction->product?->product_name}</strong> (Kode : {$transaction->transaction_id}).</p>\n" .
                    "<p>Mohon untuk dapat ditinjau, ditandatangani, dan diunggah kembali melalui tautan dibawah.</p>\n" .
                    "<p><a href=\"{$loanUploadLink}\">{$loanUploadLink}</a></p>\n\n" .
                    "<p>Terima kasih.</p>";

        // Template untuk Return Receipt
        $returnSubject = "Tanda Terima Pengembalian: {$transaction->product?->product_name} dari {$transaction->partner?->partner_name}";
        $returnUploadLink = route('public.return.upload.form', $transaction->return_upload_token);
        $returnBody = "<p>Halo,</p>\n" .
                      "<p>Terlampir adalah Tanda Terima Pengembalian untuk produk <strong>{$transaction->product?->product_name}</strong> kode : {$transaction->transaction_id}.</p>\n" .
                      "<p>Mohon untuk dapat ditinjau, ditandatangani, dan diunggah kembali melalui tautan dibawah.</p>\n" .
                      "<p><a href=\"{$returnUploadLink}\">{$returnUploadLink}</a></p>\n\n" .
                      "<p>Terima kasih atas kerja samanya.</p>";

        return response()->json([
            'success' => true,
            'templates' => [
                'loan' => [
                    'subject' => $loanSubject,
                    'body' => $loanBody,
                ],
                'return' => [
                    'subject' => $returnSubject,
                    'body' => $returnBody,
                ]
            ]
        ]);
    }

    public function sendLoanReceipt(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'manual_emails' => 'nullable|string',
            'send_to_partner' => 'boolean',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $recipientEmails = [];

        if (!empty($validated['user_ids'])) {
            $userEmails = User::whereIn('id', $validated['user_ids'])->pluck('email')->toArray();
            $recipientEmails = array_merge($recipientEmails, $userEmails);
        }

        if (!empty($validated['manual_emails'])) {
            $manualEmails = array_filter(array_map('trim', explode(',', $validated['manual_emails'])));
            $recipientEmails = array_merge($recipientEmails, $manualEmails);
        }

        if ($request->input('send_to_partner') && $transaction->partner?->email) {
            $recipientEmails[] = $transaction->partner->email;
        }

        $recipientEmails = array_unique(array_filter($recipientEmails));
        if (empty($recipientEmails)) {
            return response()->json(['message' => 'No recipients selected.'], 422);
        }
        
        $transaction->load('product.project.displayAttribute', 'partner');
        $transaction->update(['loan_receipt_status' => 'Sent']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.loan', compact('transaction'));
        
        Mail::to($recipientEmails)->send(new LoanReceiptMail(
            $transaction, 
            $pdf->output(), 
            $validated['subject'], 
            $validated['body']
        ));

        return response()->json(['message' => 'Loan receipt has been sent successfully.']);
    }

    public function sendReturnReceipt(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'manual_emails' => 'nullable|string',
            'send_to_partner' => 'boolean',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $recipientEmails = [];

        if (!empty($validated['user_ids'])) {
            $userEmails = User::whereIn('id', $validated['user_ids'])->pluck('email')->toArray();
            $recipientEmails = array_merge($recipientEmails, $userEmails);
        }

        if (!empty($validated['manual_emails'])) {
            $manualEmails = array_filter(array_map('trim', explode(',', $validated['manual_emails'])));
            $recipientEmails = array_merge($recipientEmails, $manualEmails);
        }

        if ($request->input('send_to_partner') && $transaction->partner?->email) {
            $recipientEmails[] = $transaction->partner->email;
        }

        $recipientEmails = array_unique(array_filter($recipientEmails));
        if (empty($recipientEmails)) {
            return response()->json(['message' => 'No recipients selected.'], 422);
        }
        
        $transaction->load('product.project.displayAttribute', 'partner');
        $transaction->update(['return_receipt_status' => 'Sent']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.return', compact('transaction'));

        Mail::to($recipientEmails)->send(new ReturnReceiptMail(
            $transaction, 
            $pdf->output(), 
            $validated['subject'], 
            $validated['body']
        ));

        return response()->json(['message' => 'Return receipt has been sent successfully.']);
    }

    public function downloadLoanReceipt(Transaction $transaction)
    {
        $pdf = Pdf::loadView('receipts.loan', compact('transaction'));
        $fileName = 'loan-receipt-' . $transaction->transaction_id . '.pdf';
        return $pdf->download($fileName);
    }

    public function downloadReturnReceipt(Transaction $transaction)
    {
        $pdf = Pdf::loadView('receipts.return', compact('transaction'));
        $fileName = 'return-receipt-' . $transaction->transaction_id . '.pdf';
        return $pdf->download($fileName);
    }

    public function history(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 15);
        $sortBy = $request->query('sort_by', 'updated_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = Transaction::with('product', 'partner', 'consultants')
                              ->whereIn('status', ['Completed', 'Completed (Unreturned)']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('product', function($subq) use ($search) {
                      $subq->where('product_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('partner', function($subq) use ($search) {
                      $subq->where('partner_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $transactions = $query->paginate($perPage)->appends($request->query());

        return view('transactions.history', compact('transactions'));
    }

    public function showNews(Transaction $transaction)
    {
        $newsLinks = json_decode($transaction->news_links);
        return view('transactions.news', compact('transaction', 'newsLinks'));
    }

    public function exportNews(Transaction $transaction)
    {
        $fileName = 'news-links-' . $transaction->transaction_id . '.xlsx';
        return Excel::download(new TransactionNewsExport($transaction), $fileName);
    }
}
