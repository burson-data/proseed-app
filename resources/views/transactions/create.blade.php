<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Create New Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf
                        <!-- Select Product -->
                        <div class="mt-4">
                            <x-input-label for="product_id" :value="__('Select Product')" />
                            <select id="product-select" name="product_id" required></select>
                        </div>
                        <!-- Select Partner -->
                        <div class="mt-4">
                            <x-input-label for="partner_id" :value="__('Select Partner')" />
                            <select id="partner-select" name="partner_id" required></select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="consultants" :value="__('Assign Consultants (Optional)')" />
                            <x-user-selection :users="$consultants" name="consultants" />
                            <x-input-error :messages="$errors->get('consultants')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="estimated_return_date" :value="__('Estimated Return Date')" />
                            <x-text-input id="estimated_return_date" class="block mt-1 w-full" type="date" name="estimated_return_date" :value="old('estimated_return_date')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="purpose" :value="__('Purpose')" />
                            <x-text-input id="purpose" class="block mt-1 w-full" type="text" name="purpose" :value="old('purpose')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes (Optional)')" />
                            <textarea name="notes" id="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mt-6">
                            <label for="is_unreturned" class="flex items-center">
                                <input id="is_unreturned" type="checkbox" name="is_unreturned" value="1" {{ old('is_unreturned') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Mark as Unreturned Item') }}</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">
                                Jika dicentang, request ini akan langsung masuk ke History setelah Tanda Terima Peminjaman (Loan Receipt) diunggah.
                            </p>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button class="ms-4">
                                {{ __('Save Request') }}
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
            const tomSelectConfig = (url, placeholder) => ({
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                placeholder: placeholder,
                load: function(query, callback) {
                    fetch(`${url}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(json => {
                            callback(json);
                        }).catch(()=>{
                            callback();
                        });
                }
            });

            new TomSelect('#product-select', tomSelectConfig('{{ route('select.products') }}', 'Search for a product...'));
            new TomSelect('#partner-select', tomSelectConfig('{{ route('select.partners') }}', 'Search for a partner...'));
        });
    </script>
    @endpush
</x-app-layout>