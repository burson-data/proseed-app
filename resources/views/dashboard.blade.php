<x-app-layout>

<div class="pt-24 pb-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if (session('error'))
            <div class="mb-4 bg-danger/10 border border-danger/30 text-danger p-4 rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- TOP ROW: Four Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-light-surface dark:bg-dark-surface p-6 rounded-lg shadow-sm border-l-4 border-action">
                <h3 class="text-lg font-medium text-light-text-muted dark:text-dark-text-muted">Active Products</h3>
                <p class="mt-2 text-4xl font-bold text-light-text dark:text-dark-text">{{ $activeProducts }}</p>
            </div>
            <div class="bg-light-surface dark:bg-dark-surface p-6 rounded-lg shadow-sm border-l-4 border-success">
                <h3 class="text-lg font-medium text-light-text-muted dark:text-dark-text-muted">Products on Loan</h3>
                <p class="mt-2 text-4xl font-bold text-light-text dark:text-dark-text">{{ $borrowedProducts }}</p>
            </div>
            <div class="bg-light-surface dark:bg-dark-surface p-6 rounded-lg shadow-sm border-l-4 border-accent">
                <h3 class="text-lg font-medium text-light-text-muted dark:text-dark-text-muted">Total Requests</h3>
                <p class="mt-2 text-4xl font-bold text-light-text dark:text-dark-text">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-light-surface dark:bg-dark-surface p-6 rounded-lg shadow-sm border-l-4 border-danger">
                <h3 class="text-lg font-medium text-light-text-muted dark:text-dark-text-muted">⚠️ Late Items</h3>
                <p class="mt-2 text-4xl font-bold text-light-text dark:text-dark-text">{{ $lateItems }}</p>
            </div>
        </div>

        {{-- MIDDLE ROW: Two Equal Columns (Due Soon & Overdue) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 items-stretch">

            {{-- Left Column: Items Due for Return --}}
            <div>
                <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-md sm:rounded-lg h-full flex flex-col">
                    <div class="bg-action dark:bg-action-dark p-3 rounded-t-lg">
                        <h3 class="text-lg font-bold text-white">Items Due for Return</h3>
                    </div>
                    <div class="p-6 pt-0 text-light-text dark:text-dark-text flex-grow max-h-64 overflow-y-auto">
                        <ul class="divide-y divide-gray-200 dark:divide-border-color">
                            @forelse($dueSoonTransactions as $transaction)
                                <li class="py-3">
                                    <p class="text-sm font-medium text-light-text dark:text-dark-text">{{ $transaction->product?->product_name }}</p>
                                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">
                                        Due on:
                                        <span class="font-semibold text-accent">
                                            {{ \Carbon\Carbon::parse($transaction->estimated_return_date)->format('d M Y') }}
                                        </span>
                                        (to {{ $transaction->partner?->partner_name }})
                                    </p>
                                </li>
                            @empty
                                {{-- Changed from <p> to <li> --}}
                                <li class="py-3">
                                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">No items are due in the next 7 days.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Right Column: Items Overdue (NEW CARD - TEMPORARILY MOCKED) --}}
            <div>
                <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-md sm:rounded-lg h-full flex flex-col">
                    <div class="bg-danger dark:bg-danger-dark p-3 rounded-t-lg">
                        <h3 class="text-xl font-bold text-white">Items Overdue</h3>
                    </div>
                    <div class="p-6 pt-0 text-light-text dark:text-dark-text flex-grow max-h-64 overflow-y-auto">
                        <ul class="divide-y divide-gray-200 dark:divide-border-color">
                            {{-- ... (your commented out forelse and placeholder) ... --}}
                            <li class="py-3">
                                <p class="text-sm text-light-text-muted dark:text-dark-text-muted">
                                    Data for overdue items will appear here.
                                </p>
                                <p class="text-xs text-light-text-muted dark:text-dark-text-muted">
                                    (Backend integration pending)
                                </p>
                            </li>
                            @for ($i = 0; $i < 10; $i++)
                                <li class="py-3">
                                    <p class="text-sm font-medium text-light-text dark:text-dark-text">Placeholder Item {{ $i + 1 }}</p>
                                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Due: Tomorrow (to Partner X)</p>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- BOTTOM ROW: Recent Activity (Full Width) --}}
        <div class="grid grid-cols-1 gap-6">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <h3 class="text-xl font-bold text-light-text dark:text-accent mb-4">Recent Activity</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-border-color">
                        @forelse ($recentTransactions as $transaction)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-light-text dark:text-dark-text">
                                        <span class="font-bold">{{ $transaction->product?->product_name }}</span>
                                        <span class="text-light-text-muted dark:text-dark-text-muted"> was loaned to </span>
                                        <span class="font-bold">{{ $transaction->partner?->partner_name }}</span>
                                    </p>
                                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">{{ $transaction->status }} - {{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('transactions.show', $transaction->id) }}" class="text-sm text-action hover:underline">View</a>
                            </li>
                        @empty
                            <p class="text-sm text-light-text-muted dark:text-dark-text-muted">No recent activity in this project.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
</x-app-layout>