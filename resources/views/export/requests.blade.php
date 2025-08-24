<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Export Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Export Requests Card -->
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <h3 class="text-lg font-medium mb-4">Export Request Data</h3>
                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted mb-6">
                        Choose which set of transaction data you would like to download.
                    </p>
                    <form method="POST" action="{{ route('export.requests.handle') }}">
                        @csrf
                        <div>
                            <x-input-label for="type_requests" :value="__('Export Type')" />
                            <select name="type" id="type_requests" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                                <option value="active">Active Requests Only</option>
                                <option value="history">Transaction History Only</option>
                                <option value="all">All Transactions (Active + History)</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>{{ __('Export Requests') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Export Products Card -->
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <h3 class="text-lg font-medium mb-4">Export Product Data</h3>
                    <form method="POST" action="{{ route('export.products.handle') }}">
                        @csrf
                        <div>
                            <x-input-label for="type_products" :value="__('Export Type')" />
                            <select name="type" id="type_products" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                                <option value="all">All Products</option>
                                <option value="active">Active Products Only</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>{{ __('Export Products') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Export Partners Card -->
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <h3 class="text-lg font-medium mb-4">Export Partner Data</h3>
                    <form method="POST" action="{{ route('export.partners.handle') }}">
                        @csrf
                        <div>
                            <x-input-label for="type_partners" :value="__('Export Type')" />
                            <select name="type" id="type_partners" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                                <option value="all">All Partners</option>
                                <option value="active">Active Partners Only</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>{{ __('Export Partners') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
