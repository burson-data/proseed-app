<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Exports\PartnerJourneyExport;
use Maatwebsite\Excel\Facades\Excel;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 15);
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = Partner::query();

        // Filter based on active status
        if (!$request->query('show_inactive')) {
            $query->where('is_active', true);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('partner_name', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('partner_id', 'like', "%{$search}%");
            });
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $partners = $query->paginate($perPage)->appends($request->query());

        return view('partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'partner_name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $currentProjectId = session('current_project_id');
        
        $partnerCountInProject = Partner::where('project_id', $currentProjectId)->count();
        $nextNum = $partnerCountInProject + 1;

        $cleanPartnerName = preg_replace('/[^a-zA-Z0-9]/', '', $request->partner_name);
        $cleanPicName = preg_replace('/[^a-zA-Z0-9]/', '', $request->pic_name);
        $partnerId = 'PART-' . $currentProjectId . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $dataToCreate = array_merge($validatedData, [
            'project_id' => $currentProjectId,
            'partner_id' => $partnerId,
        ]);
        
        Partner::create($dataToCreate);

        return redirect()->route('partners.index')
                         ->with('success', 'Partner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {
        return view('partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        $validatedData = $request->validate([
            'partner_name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $partner->update($validatedData);

        return redirect()->route('partners.index')
                         ->with('success', 'Partner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('partners.index')
                         ->with('success', 'Partner deleted successfully.');
    }

    /**
     * Toggle the active status of the specified partner.
     */
    public function toggleStatus(Partner $partner)
    {
        $partner->update(['is_active' => !$partner->is_active]);
        $message = $partner->is_active ? 'Partner activated successfully.' : 'Partner deactivated successfully.';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the transaction journey for a specific partner.
     */
    public function journey(Partner $partner)
    {
        $transactions = $partner->transactions()
                                ->with('product', 'consultants')
                                ->latest('borrow_date')
                                ->get();

        return view('partners.journey', compact('partner', 'transactions'));
    }

    /**
     * Handle the export of the partner's journey.
     */
    public function exportJourney(Partner $partner)
    {
        $fileName = 'partner_journey_' . $partner->partner_id . '.xlsx';
        return Excel::download(new PartnerJourneyExport($partner->id), $fileName);
    }
}
