<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Details') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <div>
                            <h3 class="text-2xl font-bold">Transaction Details</h3>
                            <p class="text-sm text-gray-500">{{ $transaction->transaction_id }}</p>
                        </div>
                        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800">&larr; Back</a>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div><span class="text-gray-600">Status:</span> <span class="font-semibold">{{ $transaction->status }}</span></div>
                        <div><span class="text-gray-600">Purpose:</span> <span class="font-semibold">{{ $transaction->purpose }}</span></div>
                        <div><span class="text-gray-600">Borrow Date:</span> <span class="font-semibold">{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d F Y') }}</span></div>
                        <div><span class="text-gray-600">Return Date:</span> <span class="font-semibold">{{ $transaction->actual_return_date ? \Carbon\Carbon::parse($transaction->actual_return_date)->format('d F Y') : 'N/A' }}</span></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-lg mb-2">Product</h4>
                            <p>{{ $transaction->product?->product_name ?? 'N/A' }} ({{ $transaction->product?->product_id ?? 'N/A' }})</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Partner</h4>
                            <p>{{ $transaction->partner?->partner_name ?? 'N/A' }} (PIC: {{ $transaction->partner?->pic_name ?? 'N/A' }})</p>
                        </div>
                         <div>
                            <h4 class="font-bold text-lg mb-2">Assigned Consultant</h4>
                            <p>{{ $transaction->consultant?->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($transaction->status != 'Active')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-lg mb-2">Return Conditions</h4>
                            @forelse($transaction->project->conditionDefinitions as $condition)
                                <div class="flex justify-between py-1 text-sm">
                                    <span class="text-gray-600">{{ $condition->name }}:</span>
                                    <span class="font-semibold">{{ $transaction->return_conditions[$condition->id] ?? 'N/A' }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No conditions defined.</p>
                            @endforelse
                        </div>
                         <div>
                            <h4 class="font-bold text-lg mb-2">News Links</h4>
                            @if(is_array(json_decode($transaction->news_links)) && !empty(json_decode($transaction->news_links)))
                                <ul class="list-disc list-inside text-sm">
                                @foreach(json_decode($transaction->news_links) as $link)
                                    <li><a href="{{ $link->link }}" target="_blank" class="text-blue-600 hover:underline">{{ $link->title }}</a></li>
                                @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500">No news links provided.</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>