<x-guest-layout>
    <div class="w-full">
        <h2 class="text-2xl font-bold text-center mb-4 text-light-text dark:text-yellow-400">Select a Project</h2>

        @if($projects->isEmpty())
            <p class="text-center text-light-text-muted dark:text-white">You are not assigned to any active projects.</p>

            @if(auth()->user()->role == 'admin')
                <div class="text-center mt-4 border-t dark:border-gray-600 pt-4">
                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted mb-2">As an admin, you can create one.</p>
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-accent dark:text-dark-bg border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Go to Project Management
                    </a>
                </div>
            @else
                <p class="text-center text-sm text-light-text-muted dark:text-dark-text-muted mt-2">Please contact an administrator.</p>
            @endif

        @else
            <div class="space-y-4">
                @foreach($projects as $project)
                    <form method="POST" action="{{ route('projects.select.submit', $project->id) }}">
                        @csrf
                        <button type="submit" class="w-full text-left p-4 border dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring">
                            <div class="font-bold text-light-text dark:text-white">{{ $project->name }}</div>
                            <div class="text-sm text-light-text-muted dark:text-white-text-muted">{{ $project->description }}</div>
                        </button>
                    </form>
                @endforeach
            </div>
        @endif

        <div class="border-t dark:border-gray-600 mt-6 pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center text-sm text-light-text-muted dark:text-dark-text-muted hover:text-light-text dark:hover:text-dark-text">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
