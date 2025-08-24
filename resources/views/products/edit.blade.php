<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Edit Product: ') }} {{ $product->product_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text">
                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div>
                            <x-input-label for="product_name" :value="__('Product Name')" />
                            <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="product_name" :value="old('product_name', $product->product_name)" required autofocus />
                        </div>

                        <!-- Key Attribute -->
                        <div class="mt-4">
                            <x-input-label for="key_attribute_value" :value="__($key_attribute_name)" />
                            <x-text-input id="key_attribute_value" class="block mt-1 w-full" type="text" name="key_attribute_value" :value="old('key_attribute_value', $product->key_attribute_value)" required />
                            <x-input-error :messages="$errors->get('key_attribute_value')" class="mt-2" />
                        </div>

                        <!-- Product Image -->
                        <div class="mt-4">
                            <x-input-label for="product_image" :value="__('Change Product Image (Optional)')" />
                            <input id="product_image" name="product_image" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-300 dark:hover:file:bg-blue-900/40"/>
                            <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                            @if($product->product_image)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                                    <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}" class="max-h-40 rounded-md shadow">
                                </div>
                            @endif
                        </div>
                        
                        <hr class="my-6 border-gray-200 dark:border-gray-700">

                        <!-- Custom Attributes -->
                        <h3 class="text-lg font-medium mb-4">Custom Attributes</h3>
                        <div class="space-y-4">
                            @foreach ($attributes as $attribute)
                                <div>
                                    <x-input-label for="attribute_{{ $attribute->id }}">
                                        {{ $attribute->name }}
                                        @if($attribute->is_required) <span class="text-danger">*</span> @endif
                                    </x-input-label>

                                    @php
                                        $value = old('attributes.' . $attribute->id, data_get($product->attributes, $attribute->id));
                                    @endphp

                                    @switch($attribute->type)
                                        @case('date')
                                            <x-text-input id="attribute_{{ $attribute->id }}" class="block mt-1 w-full" type="date" name="attributes[{{ $attribute->id }}]" :value="$value" :required="$attribute->is_required" />
                                            @break
                                        
                                        @case('dropdown')
                                            <select id="attribute_{{ $attribute->id }}" name="attributes[{{ $attribute->id }}]" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm" {{ $attribute->is_required ? 'required' : '' }}>
                                                <option value="">-- Select an option --</option>
                                                @if(is_array($attribute->options))
                                                    @foreach($attribute->options as $option)
                                                        <option value="{{ $option }}" @if($value == $option) selected @endif>{{ $option }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break

                                        @case('file')
                                            <input id="attribute_{{ $attribute->id }}" type="file" name="attributes[{{ $attribute->id }}]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-300 dark:hover:file:bg-blue-900/40">
                                            @if($value)
                                                <a href="{{ Storage::url($value) }}" target="_blank" class="text-sm mt-2 text-action hover:underline">View current file</a>
                                            @endif
                                            @break

                                        @default
                                            <x-text-input id="attribute_{{ $attribute->id }}" class="block mt-1 w-full" type="text" name="attributes[{{ $attribute->id }}]" :value="$value" :required="$attribute->is_required" />
                                    @endswitch
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-6 border-gray-200 dark:border-gray-700">

                        <h3 class="text-lg font-medium mb-4">Initial Conditions</h3>
                        <div class="space-y-4">
                            @foreach ($conditionDefinitions as $condition)
                                <div>
                                    <x-input-label for="condition_{{ $condition->id }}">{{ $condition->name }}</x-input-label>
                                    
                                    @php
                                        $value = old('conditions.' . $condition->id, data_get($product->conditions, $condition->id));
                                    @endphp

                                    @if($condition->type == 'dropdown')
                                        <select id="condition_{{ $condition->id }}" name="conditions[{{ $condition->id }}]" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                                            <option value="">-- Select an option --</option>
                                            @if(is_array($condition->options))
                                                @foreach($condition->options as $option)
                                                    <option value="{{ $option }}" @if($value == $option) selected @endif>{{ $option }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @else
                                        <x-text-input id="condition_{{ $condition->id }}" class="block mt-1 w-full" type="text" name="conditions[{{ $condition->id }}]" :value="$value" />
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}" class="text-light-text-muted dark:text-dark-text-muted">Cancel</a>
                            <x-primary-button class="ms-4">
                                {{ __('Update Product') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
