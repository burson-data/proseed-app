<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Edit Request: ') }} {{ $transaction->transaction_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Select Product -->
                        <div class="mt-4">
                            <x-input-label for="product_id" :value="__('Select Product')" />
                            <select id="product-select" name="product_id" required></select>
                             <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                        </div>

                        <!-- Select Partner -->
                        <div class="mt-4">
                            <x-input-label for="partner_id" :value="__('Select Partner')" />
                            <select id="partner-select" name="partner_id" required></select>
                             <x-input-error :messages="$errors->get('partner_id')" class="mt-2" />
                        </div>

                        <!-- Assign Consultants -->
                        <div class="mt-4">
                            <x-input-label for="consultants" :value="__('Assign Consultants (Optional)')" />
                            <x-user-selection
                                :users="$consultants"
                                :selectedUsers="$transaction->consultants->pluck('id')->toArray()"
                                name="consultants"
                            />
                            <x-input-error :messages="$errors->get('consultants')" class="mt-2" />
                        </div>

                        <!-- Estimated Return Date -->
                        <div class="mt-4">
                            <x-input-label for="estimated_return_date" :value="__('Estimated Return Date')" />
                            <x-text-input id="estimated_return_date" class="block mt-1 w-full" type="date" name="estimated_return_date" :value="old('estimated_return_date', $transaction->estimated_return_date)" required />
                            <x-input-error :messages="$errors->get('estimated_return_date')" class="mt-2" />
                        </div>

                        <!-- Purpose -->
                        <div class="mt-4">
                            <x-input-label for="purpose" :value="__('Purpose')" />
                            <x-text-input id="purpose" class="block mt-1 w-full" type="text" name="purpose" :value="old('purpose', $transaction->purpose)" required />
                             <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                        </div>
                        
                        <!-- Notes -->
                         <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes (Optional)')" />
                            <textarea name="notes" id="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">{{ old('notes', $transaction->notes) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transactions.index') }}" class="text-light-text-muted dark:text-dark-text-muted">Cancel</a>
                            <x-primary-button class="ms-4">
                                {{ __('Update Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to create Tom Select configuration
            const createTomSelect = (elementId, url, placeholder, initialOptions = []) => {
                return new TomSelect(elementId, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    placeholder: placeholder,
                    options: initialOptions, // Pre-load current selected value
                    items: initialOptions.map(item => item.value), // Pre-select the item
                    create: false,
                    load: function(query, callback) {
                        fetch(`${url}?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(json => {
                                callback(json);
                            }).catch(() => {
                                callback();
                            });
                    }
                });
            };

            // Initialize Tom Select for Products
            const initialProduct = [{
                value: '{{ $transaction->product->id }}',
                text: '{{ $transaction->product->display_name }}'
            }];
            createTomSelect('#product-select', '{{ route('select.products') }}', 'Search for a product...', initialProduct);

            // Initialize Tom Select for Partners
            const initialPartner = [{
                value: '{{ $transaction->partner->id }}',
                text: '{{ $transaction->partner->partner_name }}'
            }];
            createTomSelect('#partner-select', '{{ route('select.partners') }}', 'Search for a partner...', initialPartner);
        });
    </script>
    @endpush
</x-app-layout>
