<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text-darker dark:text-dark-text leading-tight">
            {{ __('Dashboard for Project: ') }} <span class="font-bold text-action">{{ $projectName }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-4 bg-danger/10 border border-danger/30 text-danger p-4 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            
            {{-- Layout Utama 2 Kolom --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri (Lebih Lebar) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Kartu Statistik -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    <!-- Aktivitas Terbaru -->
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

                {{-- Kolom Kanan (Lebih Sempit) --}}
                <div class="space-y-6">
                    <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-md sm:rounded-lg">
                        <div class="p-6 text-light-text dark:text-dark-text">
                            <h3 class="text-xl font-bold text-light-text dark:text-accent mb-4">Items Due for Return</h3>
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
                                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">No items are due in the next 7 days.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
