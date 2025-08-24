<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('users')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'key_attribute_name' => 'required|string|max:255', // <-- Validasi baru
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'attributes' => 'nullable|array',
            'attributes.*.name' => 'required_with:attributes|string',
            'attributes.*.type' => 'required_with:attributes|in:text,date,dropdown,file',
            'attributes.*.options' => 'nullable|string',
            'attributes.*.is_required' => 'nullable|boolean',
            'conditions' => 'nullable|array',
            'conditions.*.name' => 'required_with:conditions|string',
            'conditions.*.type' => 'required_with:conditions|in:text,dropdown',
            'conditions.*.options' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validatedData) {
            // 1. Buat proyek baru dengan key_attribute_name
            $project = Project::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'key_attribute_name' => $validatedData['key_attribute_name'], // <-- Simpan data baru
            ]);

            // 2. Assign users if any are selected
            if (isset($validatedData['users'])) {
                $project->users()->attach($validatedData['users']);
            }

            // 3. Create product attributes if defined
            if (isset($validatedData['attributes'])) {
                foreach ($validatedData['attributes'] as $attrData) {
                    $project->productAttributes()->create([
                        'name' => $attrData['name'],
                        'type' => $attrData['type'],
                        'options' => ($attrData['type'] == 'dropdown' && !empty($attrData['options'])) ? explode(',', $attrData['options']) : null,
                        'is_required' => isset($attrData['is_required']),
                    ]);
                }
            }

            // 4. Create condition definitions if defined
            if (isset($validatedData['conditions'])) {
                foreach ($validatedData['conditions'] as $condData) {
                    $project->conditionDefinitions()->create([
                        'name' => $condData['name'],
                        'type' => $condData['type'],
                        'options' => ($condData['type'] == 'dropdown' && !empty($condData['options'])) ? explode(',', $condData['options']) : null,
                    ]);
                }
            }

            if (isset($validatedData['users'])) {
                $project->users()->attach($validatedData['users']);
            }
            if (isset($validatedData['attributes'])) {
                foreach ($validatedData['attributes'] as $attrData) {
                    $project->productAttributes()->create([
                        'name' => $attrData['name'],
                        'type' => $attrData['type'],
                        'options' => ($attrData['type'] == 'dropdown' && !empty($attrData['options'])) ? explode(',', $attrData['options']) : null,
                        'is_required' => isset($attrData['is_required']),
                    ]);
                }
            }
            if (isset($validatedData['conditions'])) {
                foreach ($validatedData['conditions'] as $condData) {
                    $project->conditionDefinitions()->create([
                        'name' => $condData['name'],
                        'type' => $condData['type'],
                        'options' => ($condData['type'] == 'dropdown' && !empty($condData['options'])) ? explode(',', $condData['options']) : null,
                    ]);
                }
            }
        });

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // This can be used for a detailed project view page if needed in the future.
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $project->load('productAttributes', 'conditionDefinitions');
        $users = User::all();
        
        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_attribute_id' => 'nullable|exists:product_attributes,id', // Validasi
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'attributes' => 'nullable|array',
            'attributes.*.name' => 'required|string',
            'attributes.*.type' => 'required|in:text,date,dropdown,file',
            'attributes.*.options' => 'nullable|string',
            'attributes.*.is_required' => 'nullable|boolean',
            'conditions' => 'nullable|array',
            'conditions.*.name' => 'required_with:conditions|string',
            'conditions.*.type' => 'required_with:conditions|in:text,dropdown',
            'conditions.*.options' => 'nullable|string',
            'key_attribute_name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validatedData, $project) {
            // 1. Update detail dasar proyek, termasuk display_attribute_id
            $project->update([
                'key_attribute_name' => $validatedData['key_attribute_name'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'display_attribute_id' => $validatedData['display_attribute_id'] ?? null,
            ]);
            // 2. Sync assigned users
            $project->users()->sync($validatedData['users'] ?? []);

            // 3. Sync product attributes
            if (isset($validatedData['attributes'])) {
                $existingIds = [];
                foreach ($validatedData['attributes'] as $key => $attrData) {
                    $attribute = $project->productAttributes()->updateOrCreate(
                        ['id' => is_numeric($key) ? $key : null],
                        [
                            'name' => $attrData['name'],
                            'type' => $attrData['type'],
                            'options' => ($attrData['type'] == 'dropdown' && !empty($attrData['options'])) ? explode(',', $attrData['options']) : null,
                            'is_required' => isset($attrData['is_required']),
                        ]
                    );
                    $existingIds[] = $attribute->id;
                }
                $project->productAttributes()->whereNotIn('id', $existingIds)->delete();
            } else {
                $project->productAttributes()->delete();
            }

            // 4. Sync condition definitions
            if (isset($validatedData['conditions'])) {
                $existingIds = [];
                foreach ($validatedData['conditions'] as $key => $condData) {
                    $condition = $project->conditionDefinitions()->updateOrCreate(
                        ['id' => is_numeric($key) ? $key : null],
                        [
                            'name' => $condData['name'],
                            'type' => $condData['type'],
                            'options' => ($condData['type'] == 'dropdown' && !empty($condData['options'])) ? explode(',', $condData['options']) : null,
                        ]
                    );
                    $existingIds[] = $condition->id;
                }
                $project->conditionDefinitions()->whereNotIn('id', $existingIds)->delete();
            } else {
                $project->conditionDefinitions()->delete();
            }
        });

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Deactivate or activate the specified project.
     */
    public function destroy(Project $project)
    {
        $newStatus = $project->status == 'active' ? 'inactive' : 'active';
        $project->update(['status' => $newStatus]);
        
        $message = $newStatus == 'active' ? 'Project activated successfully.' : 'Project deactivated successfully.';

        return redirect()->route('projects.index')->with('success', $message);
    }
    
    /**
     * Show the project selection screen.
     */
    public function showSelection()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $projects = $user->projects()->where('status', 'active')->get();

        if ($projects->count() == 1) {
            session(['current_project_id' => $projects->first()->id]);
            return redirect()->route('dashboard');
        }
        
        return view('projects.select', compact('projects'));
    }

    /**
     * Handle the project selection and save it to the session.
     */
    public function selectProject(Project $project)
    {
        if (!Auth::user()->projects->contains($project)) {
            abort(403);
        }

        session(['current_project_id' => $project->id]);

        return redirect()->route('dashboard');
    }
}
