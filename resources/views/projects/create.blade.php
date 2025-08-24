<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Create New Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                <!-- Project Details Card -->
                <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-light-text dark:text-dark-text">
                        <h3 class="text-lg font-medium mb-4">Project Details</h3>
                        <!-- Project Name -->
                        <div>
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea name="description" id="description" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">{{ old('description') }}</textarea>
                        </div>

                        <!-- === TAMBAHKAN BLOK INI === -->
                        <!-- Key Attribute Name -->
                        <div class="mt-4">
                            <x-input-label for="key_attribute_name" :value="__('Key Attribute Name')" />
                            <x-text-input id="key_attribute_name" class="block mt-1 w-full" type="text" name="key_attribute_name" :value="old('key_attribute_name', 'Identifier')" required />
                            <p class="text-sm text-gray-500 mt-1">Ini akan menjadi label untuk pengenal unik produk (contoh: IMEI, Nomor Seri).</p>
                            <x-input-error :messages="$errors->get('key_attribute_name')" class="mt-2" />
                        </div>
                        <!-- ========================= -->

                        <!-- Assign Users -->
                        <div class="mt-4">
                            <x-input-label for="users" :value="__('Assign Users to this Project')" />
                            <x-user-selection :users="$users" />
                            <x-input-error :messages="$errors->get('users')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Custom Product Attributes Card -->
                <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-light-text dark:text-dark-text">
                        <h3 class="text-lg font-medium mb-4">Custom Product Attributes</h3>
                        <p class="text-sm text-light-text-muted dark:text-dark-text-muted mb-4">Define the fields that each product in this project will have.</p>
                        <div id="attributes-container" class="space-y-4">
                            {{-- New attribute rows will be added here by JavaScript --}}
                        </div>
                        <button type="button" id="add-attribute-btn" class="mt-4 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-light-text dark:text-dark-text font-bold py-2 px-4 rounded text-sm">+ Add Attribute</button>
                    </div>
                </div>

                <!-- Product Condition Definitions Card -->
                <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-light-text dark:text-dark-text">
                        <h3 class="text-lg font-medium mb-4">Product Condition Checkpoints</h3>
                        <p class="text-sm text-light-text-muted dark:text-dark-text-muted mb-4">Define the condition checkpoints for receiving and returning products.</p>
                        <div id="conditions-container" class="space-y-4">
                            {{-- New condition rows will be added here by JavaScript --}}
                        </div>
                        <button type="button" id="add-condition-btn" class="mt-4 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-light-text dark:text-dark-text font-bold py-2 px-4 rounded text-sm">+ Add Condition</button>
                    </div>
                </div>
                
                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('projects.index') }}" class="text-light-text-muted dark:text-dark-text-muted">Cancel</a>
                    <x-primary-button class="ms-4">
                        {{ __('Create Project') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- LOGIC FOR DYNAMIC ATTRIBUTES ---
            const attrContainer = document.getElementById('attributes-container');
            const addAttrBtn = document.getElementById('add-attribute-btn');
            let attrIndex = Date.now();

            function handleAttrTypeChange(event) {
                const select = event.target;
                const optionsContainer = select.closest('.attribute-row').querySelector('.options-container');
                optionsContainer.style.display = select.value === 'dropdown' ? 'block' : 'none';
            }

            function attachAttrEventListeners(row) {
                row.querySelector('.remove-attribute-btn').addEventListener('click', function() { row.remove(); });
                row.querySelector('.attribute-type-select').addEventListener('change', handleAttrTypeChange);
            }

            addAttrBtn.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'p-4 border dark:border-gray-600 rounded-md flex items-center gap-4 attribute-row';
                newRow.innerHTML = `
                    <div class="flex-1"><label class="block text-sm font-medium">Attribute Name</label><input type="text" name="attributes[new_${attrIndex}][name]" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm" required></div>
                    <div class="flex-1"><label class="block text-sm font-medium">Attribute Type</label><select name="attributes[new_${attrIndex}][type]" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm attribute-type-select"><option value="text">Short Text</option><option value="date">Date</option><option value="dropdown">Dropdown</option><option value="file">File Upload</option></select></div>
                    <div class="flex-1 options-container" style="display:none;"><label class="block text-sm font-medium">Dropdown Options (comma-separated)</label><input type="text" name="attributes[new_${attrIndex}][options]" placeholder="e.g., Red,Green,Blue" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm"></div>
                    <div class="flex items-center pt-6"><input type="checkbox" name="attributes[new_${attrIndex}][is_required]" value="1" class="rounded border-gray-300 dark:border-gray-600"><label class="ml-2 text-sm">Required</label></div>
                    <div class="pt-6"><button type="button" class="text-danger remove-attribute-btn">Remove</button></div>
                `;
                attrContainer.appendChild(newRow);
                attachAttrEventListeners(newRow);
                attrIndex++;
            });

            // --- LOGIC FOR DYNAMIC CONDITIONS ---
            const condContainer = document.getElementById('conditions-container');
            const addCondBtn = document.getElementById('add-condition-btn');
            let condIndex = Date.now();

            function handleCondTypeChange(event) {
                const select = event.target;
                const optionsContainer = select.closest('.condition-row').querySelector('.options-container');
                optionsContainer.style.display = select.value === 'dropdown' ? 'block' : 'none';
            }

            function attachCondEventListeners(row) {
                row.querySelector('.remove-condition-btn').addEventListener('click', function() { row.remove(); });
                row.querySelector('.condition-type-select').addEventListener('change', handleCondTypeChange);
            }

            addCondBtn.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'p-4 border dark:border-gray-600 rounded-md flex items-center gap-4 condition-row';
                newRow.innerHTML = `
                    <div class="flex-1"><label class="block text-sm font-medium">Condition Name</label><input type="text" name="conditions[new_${condIndex}][name]" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm" required></div>
                    <div class="flex-1"><label class="block text-sm font-medium">Input Type</label><select name="conditions[new_${condIndex}][type]" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm condition-type-select"><option value="text">Short Text</option><option value="dropdown">Dropdown</option></select></div>
                    <div class="flex-1 options-container" style="display:none;"><label class="block text-sm font-medium">Dropdown Options (comma-separated)</label><input type="text" name="conditions[new_${condIndex}][options]" placeholder="e.g., Good,Scratched,Broken" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm"></div>
                    <div class="pt-6"><button type="button" class="text-danger remove-condition-btn">Remove</button></div>
                `;
                condContainer.appendChild(newRow);
                attachCondEventListeners(newRow);
                condIndex++;
            });
        });
    </script>
</x-app-layout>
