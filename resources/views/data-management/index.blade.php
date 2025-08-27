<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Import Column -->
                <div class="space-y-6">
                    <!-- Product Import Card -->
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Import Products') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Upload an Excel file to import new products.') }}
                                </p>
                            </header>
                            <form method="post" action="{{ route('data-management.import.products') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <x-input-label for="product_file" :value="__('Excel File')" />
                                    <x-text-input id="product_file" name="product_file" type="file" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('product_file')" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Import Products') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Partner Import Card -->
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Import Partners') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Upload an Excel file to import new partners.') }}
                                </p>
                            </header>
                            <form method="post" action="{{ route('data-management.import.partners') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <x-input-label for="partner_file" :value="__('Excel File')" />
                                    <x-text-input id="partner_file" name="partner_file" type="file" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('partner_file')" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Import Partners') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Export Column -->
                <div class="space-y-6">
                    <!-- Export Requests Card -->
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <h3 class="text-lg font-medium mb-4">Export Request Data</h3>
                            <form method="POST" action="{{ route('data-management.export.requests') }}">
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
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <h3 class="text-lg font-medium mb-4">Export Product Data</h3>
                            <form method="POST" action="{{ route('data-management.export.products') }}">
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
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <h3 class="text-lg font-medium mb-4">Export Partner Data</h3>
                            <form method="POST" action="{{ route('data-management.export.partners') }}">
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
        </div>
    </div>
</x-app-layout>
