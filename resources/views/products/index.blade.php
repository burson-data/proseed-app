<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-success/10 border border-success/30 text-success p-4 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <!-- Kiri: Search & Filter -->
                        <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-4">
                            <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" class="border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                            <button type="submit" class="bg-gray-800 dark:bg-accent dark:text-dark-bg text-white font-bold py-2 px-4 rounded-md hover:bg-gray-700 dark:hover:bg-yellow-400">Search</button>
                            
                            {{-- CHECKBOX UNTUK MENAMPILKAN YANG TIDAK AKTIF --}}
                            <label class="flex items-center space-x-2 text-sm text-light-text-muted dark:text-dark-text-muted">
                                <input type="checkbox" name="show_inactive" value="true" onchange="this.form.submit()" @if(request('show_inactive')) checked @endif class="rounded border-gray-300 dark:border-gray-600 text-action focus:ring-action">
                                <span>Show Inactive</span>
                            </label>
                        </form>

                        <!-- Kanan: Tombol Add New -->
                        <a href="{{ route('products.create') }}" class="bg-action hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Product
                        </a>
                    </div>

                    <div class="overflow-x-auto" style="min-height: 350px;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-border-color">
                            <thead class="bg-gray-900">
                                <tr>
                                    @php
                                        $createSortLink = function ($sortBy, $label) {
                                            $sortOrder = (request('sort_by') === $sortBy && request('sort_order') === 'asc') ? 'desc' : 'asc';
                                            $url = route('products.index', array_merge(request()->query(), ['sort_by' => $sortBy, 'sort_order' => $sortOrder]));
                                            $icon = 'fa-sort';
                                            if (request('sort_by') === $sortBy) {
                                                $icon = request('sort_order') === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
                                            }
                                            return '<a href="'.$url.'" class="flex items-center">'.$label.'<i class="fas '.$icon.' ms-2 text-gray-300"></i></a>';
                                        };
                                    @endphp
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">{!! $createSortLink('product_name', 'Product Name') !!}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">{!! $createSortLink('key_attribute_value', $project->key_attribute_name) !!}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">{!! $createSortLink('status', 'Status') !!}</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-light-surface dark:bg-dark-surface divide-y divide-gray-200 dark:divide-border-color">
                                @forelse ($products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer {{ !$product->is_active ? 'opacity-50' : '' }}" onclick="window.location='{{ route('products.show', $product->id) }}';">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->product_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->key_attribute_value }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->status }}</td>
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
                                                        <x-dropdown-link :href="route('products.show', $product->id)">{{ __('View Details') }}</x-dropdown-link>
                                                        <x-dropdown-link :href="route('products.edit', $product->id)">{{ __('Edit') }}</x-dropdown-link>
                                                        <x-dropdown-link :href="route('products.journey', $product->id)">{{ __('View Journey') }}</x-dropdown-link>
                                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                                        
                                                        <form method="POST" action="{{ route('products.toggleStatus', $product->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <x-dropdown-link :href="route('products.toggleStatus', $product->id)" onclick="event.preventDefault(); this.closest('form').submit();" class="{{ $product->is_active ? 'text-danger' : 'text-success' }}">
                                                                {{ $product->is_active ? __('Deactivate') : __('Activate') }}
                                                            </x-dropdown-link>
                                                        </form>
                                                    </x-slot>
                                                </x-dropdown>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-light-text-muted dark:text-dark-text-muted">
                                            No products found in this project.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <select id="per_page_select" class="border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                                <option value="15" @if(request('per_page', 15) == 15) selected @endif>15 per page</option>
                                <option value="50" @if(request('per_page') == 50) selected @endif>50 per page</option>
                                <option value="100" @if(request('per_page') == 100) selected @endif>100 per page</option>
                            </select>
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('per_page_select').addEventListener('change', function() {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('per_page', this.value);
            urlParams.set('page', 1);
            window.location.search = urlParams.toString();
        });
    </script>
</x-app-layout>
