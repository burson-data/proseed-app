<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Manage Projects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-success/10 border border-success/30 text-success p-4 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('projects.create') }}" class="bg-action hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Project
                        </a>
                    </div>

                    <div class="overflow-x-auto" style="min-height: 350px;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-border-color">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Project Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Assigned Users</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-light-surface dark:bg-dark-surface divide-y divide-gray-200 dark:divide-border-color">
                                @forelse ($projects as $project)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $project->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $project->users->count() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($project->status == 'active')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-4">
                                                <a href="{{ route('projects.edit', $project->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit / Assign</a>
                                                
                                                <form method="POST" action="{{ route('projects.destroy', $project->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    @if($project->status == 'active')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to deactivate this project?')">
                                                            Deactivate
                                                        </button>
                                                    @else
                                                         <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Are you sure you want to activate this project?')">
                                                            Activate
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-light-text-muted dark:text-dark-text-muted">
                                            No projects found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            {{-- Dropdown per_page bisa ditambahkan di sini jika perlu --}}
                        </div>
                        <div>
                            {{ $projects->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>