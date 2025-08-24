<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Request History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    
                    <div class="flex justify-between items-center mb-4">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('history.index') }}" class="flex items-center space-x-2">
                            <input type="text" name="search" placeholder="Search history..." value="{{ request('search') }}" class="border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                            <button type="submit" class="bg-gray-800 dark:bg-accent dark:text-dark-bg text-white font-bold py-2 px-4 rounded-md hover:bg-gray-700 dark:hover:bg-yellow-400">Search</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto" style="min-height: 350px;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-border-color">
                            <thead class="bg-gray-900">
                                <tr>
                                     @php
                                        $createSortLink = fn($label, $sortBy) => '<a href="'.route('history.index', array_merge(request()->query(), ['sort_by' => $sortBy, 'sort_order' => request('sort_by') === $sortBy && request('sort_order') === 'asc' ? 'desc' : 'asc'])).'">'.$label.'<i class="fas '.(request('sort_by') === $sortBy ? (request('sort_order') === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort').' ms-2 text-gray-300"></i></a>';
                                    @endphp
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Partner</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">{!! $createSortLink('actual_return_date', 'Return Date') !!}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">{!! $createSortLink('status', 'Status') !!}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Loan Receipt</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Return Receipt</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-light-surface dark:bg-dark-surface divide-y divide-gray-200 dark:divide-border-color">
                                @forelse ($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location='{{ route('transactions.show', $transaction->id) }}';">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->product?->display_name ?? 'Product Deleted' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->partner?->partner_name ?? 'Partner Deleted' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->actual_return_date)
                                                {{ \Carbon\Carbon::parse($transaction->actual_return_date)->format('d M Y') }}
                                            @else
                                                (Unreturned)
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->status }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $loanStatusClass = 'bg-red-100 text-red-800';
                                                if ($transaction->loan_receipt_status == 'Sent') $loanStatusClass = 'bg-yellow-100 text-yellow-800';
                                                elseif ($transaction->loan_receipt_status == 'Uploaded') $loanStatusClass = 'bg-green-100 text-green-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $loanStatusClass }}">
                                                {{ $transaction->loan_receipt_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $returnStatusClass = 'bg-gray-100 text-gray-800';
                                                if ($transaction->return_receipt_status == 'Sent') $returnStatusClass = 'bg-yellow-100 text-yellow-800';
                                                elseif ($transaction->return_receipt_status == 'Uploaded') $returnStatusClass = 'bg-green-100 text-green-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $returnStatusClass }}">
                                                {{ $transaction->return_receipt_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div onclick="event.stopPropagation();">
                                                <x-dropdown align="right" width="48">
                                                    <x-slot name="trigger">
                                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-light-text-muted dark:text-dark-text-muted bg-light-surface dark:bg-dark-surface hover:text-light-text dark:hover:text-dark-text focus:outline-none">
                                                            <div>Actions</div>
                                                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                                        </button>
                                                    </x-slot>
                                                    <x-slot name="content">
                                                        <x-dropdown-link :href="route('transactions.show', $transaction->id)">{{ __('View Details') }}</x-dropdown-link>
                                                        @if($transaction->news_links && $transaction->news_links !== '[]')
                                                            <x-dropdown-link :href="route('transactions.news', $transaction->id)">{{ __('View News') }}</x-dropdown-link>
                                                        @endif
                                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                                        @if($transaction->loan_receipt_status == 'Uploaded')
                                                            <a href="{{ Storage::url($transaction->loan_receipt_path) }}" target="_blank" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                                                {{ __('View Loan Receipt') }}
                                                            </a>
                                                        @endif
                                                        @if($transaction->return_receipt_status == 'Uploaded')
                                                            <a href="{{ Storage::url($transaction->return_receipt_path) }}" target="_blank" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                                                {{ __('View Return Receipt') }}
                                                            </a>
                                                        @endif
                                                    </x-slot>
                                                </x-dropdown>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-light-text-muted dark:text-dark-text-muted">
                                            No transaction history found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <select id="per_page_select_history" class="border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                                <option value="15" @if(request('per_page', 15) == 15) selected @endif>15 per page</option>
                                <option value="50" @if(request('per_page') == 50) selected @endif>50 per page</option>
                                <option value="100" @if(request('per_page') == 100) selected @endif>100 per page</option>
                            </select>
                        </div>
                        <div>
                            {{ $transactions->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('per_page_select_history').addEventListener('change', function() {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('per_page', this.value);
            urlParams.set('page', 1);
            window.location.search = urlParams.toString();
        });
    </script>
</x-app-layout>
