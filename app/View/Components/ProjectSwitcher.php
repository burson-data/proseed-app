<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class ProjectSwitcher extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = Auth::user();
        $currentProjectId = session('current_project_id');

        // Ambil nama proyek yang sedang aktif
        $currentProject = $user->projects->find($currentProjectId);

        // Ambil semua proyek lain yang bisa dipilih oleh user
        $availableProjects = $user->projects
                                ->where('status', 'active')
                                ->where('id', '!=', $currentProjectId);

        return view('components.project-switcher', [
            'currentProjectName' => $currentProject?->name ?? 'No Project Selected',
            'availableProjects' => $availableProjects,
        ]);
    }
}
