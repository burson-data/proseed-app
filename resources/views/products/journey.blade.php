<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            Product Journey: {{ $product->product_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('products.index') }}" class="text-action hover:underline">&larr; Back to Product List</a>
                        
                        <a href="{{ route('products.journey.export', $product->id) }}" class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Export to Excel
                        </a>
                    </div>

                    <div class="overflow-x-auto" style="min-height: 350px;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-border-color">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Partner</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Borrow Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Return Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Return Conditions</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">News</th>
                                </tr>
                            </thead>
                            <tbody class="bg-light-surface dark:bg-dark-surface divide-y divide-gray-200 dark:divide-border-color">
                                @forelse ($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->partner?->partner_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->actual_return_date ? \Carbon\Carbon::parse($transaction->actual_return_date)->format('d M Y') : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->status }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($transaction->return_conditions && is_array($transaction->return_conditions))
                                                @foreach($transaction->return_conditions as $key => $value)
                                                    <span><strong>{{ $transaction->project?->conditionDefinitions?->find($key)?->name ?? 'Condition' }}:</strong> {{ $value }}</span><br>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($transaction->news_links && $transaction->news_links !== '[]')
                                                <a href="{{ route('transactions.news', $transaction->id) }}" class="text-action hover:underline">View Links</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-light-text-muted dark:text-dark-text-muted">
                                            No transaction history found for this product.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>