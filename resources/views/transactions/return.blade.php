<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Process Return for Transaction: ') }} {{ $transaction->transaction_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <!-- Read-Only Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Request Details</h3>
                        <p><strong>Product:</strong> {{ $transaction->product->product_name }}</p>
                        <p><strong>Partner:</strong> {{ $transaction->partner->partner_name }}</p>
                        <p><strong>Borrow Date:</strong> {{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d F Y') }}</p>
                    </div>

                    <form method="POST" action="{{ route('transactions.processReturn', $transaction->id) }}">
                        @csrf

                        <!-- Actual Return Date -->
                        <div class="mt-4">
                            <x-input-label for="actual_return_date" :value="__('Actual Return Date')" />
                            <x-text-input id="actual_return_date" class="block mt-1 w-full" type="date" name="actual_return_date" :value="old('actual_return_date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('actual_return_date')" class="mt-2" />
                        </div>

                        <!-- Return Notes -->
                        <div class="mt-4">
                            <x-input-label for="return_notes" :value="__('Return Notes (Optional)')" />
                            <textarea name="return_notes" id="return_notes" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">{{ old('return_notes') }}</textarea>
                        </div>

                        <!-- Product Condition on Return -->
                        <hr class="my-6 border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium mb-4">Product Condition on Return</h3>
                        <p class="text-sm text-light-text-muted dark:text-dark-text-muted mb-4">The product's last known condition is pre-filled. Please update if there are any changes.</p>

                        <div class="space-y-4">
                            @foreach($conditionDefinitions as $condition)
                                <div>
                                    <x-input-label for="return_condition_{{ $condition->id }}" :value="__($condition->name)" />
                                    @php
                                        $value = old('return_conditions.' . $condition->id, data_get($transaction->product->conditions, $condition->id));
                                    @endphp
                                    @if($condition->type == 'dropdown')
                                        <select id="return_condition_{{ $condition->id }}" name="return_conditions[{{ $condition->id }}]" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                                            <option value="">-- Select an option --</option>
                                            @if(is_array($condition->options))
                                                @foreach($condition->options as $option)
                                                    <option value="{{ $option }}" @if($value == $option) selected @endif>{{ $option }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @else
                                        <x-text-input id="return_condition_{{ $condition->id }}" class="block mt-1 w-full" type="text" name="return_conditions[{{ $condition->id }}]" :value="$value" />
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- News Links -->
                        <hr class="my-6 border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium mb-4">News Links (Optional)</h3>
                        <div id="news-links-container">
                            <!-- Dynamic inputs will be added here -->
                        </div>
                        <button type="button" id="add-news-link-btn" class="mt-2 text-sm text-action hover:underline">+ Add News Link</button>
                        

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transactions.index') }}" class="text-light-text-muted dark:text-dark-text-muted">Cancel</a>
                            <x-primary-button class="ms-4">
                                {{ __('Submit Return') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-news-link-btn').addEventListener('click', function() {
            const container = document.getElementById('news-links-container');
            const newRow = document.createElement('div');
            newRow.className = 'flex items-center gap-2 mb-2';
            newRow.innerHTML = `
                <input type="text" name="news_title[]" placeholder="News Title" class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                <input type="url" name="news_link[]" placeholder="https://..." class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                <button type="button" class="text-danger remove-news-btn">X</button>
            `;
            container.appendChild(newRow);
        });

        document.getElementById('news-links-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-news-btn')) {
                e.target.parentElement.remove();
            }
        });
    </script>
</x-app-layout>
