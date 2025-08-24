<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    
                    {{-- Header Section with Actions --}}
                    <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-200 dark:border-border-color">
                        <div>
                            <h3 class="text-2xl font-bold">
                                {{ $product->product_name }}
                            </h3>
                            <p class="text-sm text-light-text-muted dark:text-dark-text-muted">{{ $product->product_id }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                             <a href="{{ route('products.journey', $product->id) }}" class="bg-purple-100 text-purple-700 hover:bg-purple-200 font-semibold py-2 px-4 rounded text-sm">
                                View Journey
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="bg-action text-white font-semibold py-2 px-4 rounded text-sm">
                                Edit Product
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- Main Details Column --}}
                        <div class="md:col-span-2 space-y-6">
                            {{-- Product Image --}}
                            @if($product->product_image)
                                <div>
                                    <h4 class="text-lg font-semibold text-light-text dark:text-dark-text mb-2">Product Image</h4>
                                    <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}" class="max-w-xs h-auto rounded-lg shadow">
                                </div>
                            @endif

                            {{-- Custom Attributes --}}
                            <div>
                                <h4 class="text-lg font-semibold text-light-text dark:text-dark-text">Attributes</h4>
                                <div class="mt-2 space-y-2 border-t border-gray-200 dark:border-border-color pt-2">
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">{{ $product->project->key_attribute_name }}</dt>
                                        <dd class="text-sm font-semibold">{{ $product->key_attribute_value }}</dd>
                                    </div>
                                    @forelse($product->project->productAttributes as $attribute)
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">{{ $attribute->name }}</dt>
                                        <dd class="text-sm">
                                            @if($attribute->type === 'file' && data_get($product->attributes, $attribute->id))
                                                <a href="{{ Storage::url(data_get($product->attributes, $attribute->id)) }}" target="_blank" class="text-action hover:underline">View File</a>
                                            @else
                                                {{ data_get($product->attributes, $attribute->id, 'N/A') }}
                                            @endif
                                        </dd>
                                    </div>
                                    @empty
                                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">No additional attributes defined.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Stats & Conditions Column --}}
                        <div class="md:col-span-1 space-y-6">
                            {{-- Activity Summary --}}
                            <div>
                                <h4 class="text-lg font-semibold text-light-text dark:text-dark-text">Activity Summary</h4>
                                <div class="mt-2 space-y-4">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                        <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Status</dt>
                                        <dd class="text-lg font-bold mt-1">{{ $product->status }}</dd>
                                    </div>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                        <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Total Requests</dt>
                                        <dd class="text-2xl font-bold mt-1">{{ $product->transactions->count() }}</dd>
                                    </div>
                                </div>
                            </div>

                            {{-- Current Conditions --}}
                            <div>
                                <h4 class="text-lg font-semibold text-light-text dark:text-dark-text">Current Conditions</h4>
                                <div class="mt-2 space-y-2 border-t border-gray-200 dark:border-border-color pt-2">
                                    @forelse($product->project->conditionDefinitions as $condition)
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">{{ $condition->name }}</dt>
                                        <dd class="text-sm">{{ data_get($product->conditions, $condition->id, 'N/A') }}</dd>
                                    </div>
                                    @empty
                                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">No conditions defined.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
