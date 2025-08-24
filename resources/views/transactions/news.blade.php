<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            News Links for Transaction: {{ $transaction->transaction_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('history.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to History</a>
                        <a href="{{ route('transactions.news.export', $transaction->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Export to Excel
                        </a>

                    </div>

                    <ul class="divide-y divide-gray-200">
                        @forelse($newsLinks as $link)
                            <li class="py-4 flex">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $link->title }}</p>
                                    <a href="{{ $link->link }}" target="_blank" class="text-sm text-blue-500 hover:underline break-all">{{ $link->link }}</a>
                                </div>
                            </li>
                        @empty
                            <p>No news links found for this transaction.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>