<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductJourneyExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentProjectId = session('current_project_id');
        $project = \App\Models\Project::find($currentProjectId);

        $displayAttributeName = $project?->key_attribute_name ?? 'Identifier';

        $search = $request->query('search');
        $perPage = $request->query('per_page', 15);
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = \App\Models\Product::query();
        
        if (!$request->query('show_inactive')) {
            $query->where('is_active', true);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('key_attribute_value', 'like', "%{$search}%")
                  ->orWhere('product_id', 'like', "%{$search}%");
            });
        }
        
        $query->orderBy($sortBy, $sortOrder);
        $products = $query->paginate($perPage)->appends($request->query());

        return view('products.index', compact('products', 'project', 'displayAttributeName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentProjectId = session('current_project_id');
        $project = Project::with('productAttributes', 'conditionDefinitions')->findOrFail($currentProjectId);

        return view('products.create', [
            'key_attribute_name' => $project->key_attribute_name,
            'attributes' => $project->productAttributes,
            'conditionDefinitions' => $project->conditionDefinitions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentProjectId = session('current_project_id');
        $request->validate([
            'product_name' => 'required|string|max:255',
            'key_attribute_value' => 'required|string|max:255|unique:products,key_attribute_value,NULL,id,project_id,' . $currentProjectId,
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'nullable|array',
            'conditions' => 'nullable|array',
        ]);
        
        $productCountInProject = Product::where('project_id', $currentProjectId)->count();
        $nextNum = $productCountInProject + 1;
        $productId = 'PROD-' . $currentProjectId . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $dataToCreate = [
            'project_id' => $currentProjectId,
            'product_id' => $productId,
            'product_name' => $request->product_name,
            'key_attribute_value' => $request->key_attribute_value,
            'status' => 'Available',
            'attributes' => $request->input('attributes', []),
            'conditions' => $request->input('conditions', []),
        ];

        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('product_images', 'public');
            $dataToCreate['product_image'] = $path;
        }

        Product::create($dataToCreate);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('project.productAttributes', 'project.conditionDefinitions');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $project = Project::with('productAttributes', 'conditionDefinitions')
                          ->findOrFail($product->project_id);

        return view('products.edit', [
            'product' => $product,
            'key_attribute_name' => $project->key_attribute_name,
            'attributes' => $project->productAttributes,
            'conditionDefinitions' => $project->conditionDefinitions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'key_attribute_value' => 'required|string|max:255|unique:products,key_attribute_value,' . $product->id . ',id,project_id,' . $product->project_id,
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'nullable|array',
            'conditions' => 'nullable|array',
        ]);
        
        $dataToUpdate = [
            'product_name' => $request->product_name,
            'key_attribute_value' => $request->key_attribute_value,
            'attributes' => $request->input('attributes', []),
            'conditions' => $request->input('conditions', []),
        ];

        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            $path = $request->file('product_image')->store('product_images', 'public');
            $dataToUpdate['product_image'] = $path;
        }

        $product->update($dataToUpdate);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle the active status of the specified product.
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $message = $product->is_active ? 'Product activated successfully.' : 'Product deactivated successfully.';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the transaction journey for a specific product.
     */
    public function journey(Product $product)
    {
        $transactions = $product->transactions()
                                ->with('partner', 'consultants', 'project.conditionDefinitions')
                                ->latest('borrow_date')
                                ->get();

        return view('products.journey', compact('product', 'transactions'));
    }

    /**
     * Handle the export of the product's journey.
     */
    public function exportJourney(Product $product)
    {
        $fileName = 'product_journey_' . $product->product_id . '.xlsx';
        return Excel::download(new ProductJourneyExport($product->id), $fileName);
    }
}
