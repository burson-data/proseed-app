@if(auth()->check() && session()->has('current_project_id'))
    <div class="hidden sm:flex sm:items-center sm:ms-6">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                {{-- Tombol ini sekarang disesuaikan untuk tema gelap --}}
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-light-header-text dark:text-dark-text bg-light-header dark:bg-dark-surface hover:text-gray-300 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                    <div>{{ $currentProjectName }}</div>

                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>
            {{-- ------------------------------------------- --}}

            <x-slot name="content">
                <div class="px-4 py-2 text-xs text-light-text-muted dark:text-dark-text-muted">
                    Switch Project
                </div>
                
                {{-- Tampilkan daftar proyek lain yang bisa dipilih --}}
                @foreach($availableProjects as $project)
                    <form method="POST" action="{{ route('projects.select.submit', $project->id) }}">
                        @csrf
                        <x-dropdown-link :href="route('projects.select.submit', $project->id)"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ $project->name }}
                        </x-dropdown-link>
                    </form>
                @endforeach

                {{-- Link untuk kembali ke halaman pilihan utama (jika ada banyak) --}}
                @if(Auth::user() && Auth::user()->projects->count() > 1)
                <div class="border-t border-gray-200 dark:border-gray-600"></div>
                <x-dropdown-link :href="route('projects.select')">
                    {{ __('View All Projects') }}
                </x-dropdown-link>
                @endif
            </x-slot>
        </x-dropdown>
    </div>
@endif
