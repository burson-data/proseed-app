<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Partner Details') }}
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
                                {{ $partner->partner_name }}
                            </h3>
                            <p class="text-sm text-light-text-muted dark:text-dark-text-muted">{{ $partner->partner_id }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                             <a href="{{ route('partners.journey', $partner->id) }}" class="bg-purple-100 text-purple-700 hover:bg-purple-200 font-semibold py-2 px-4 rounded text-sm">
                                View Journey
                            </a>
                            <a href="{{ route('partners.edit', $partner->id) }}" class="bg-action text-white font-semibold py-2 px-4 rounded text-sm">
                                Edit Partner
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- Main Details Column --}}
                        <div class="md:col-span-2 space-y-4">
                            <h4 class="text-lg font-semibold text-light-text dark:text-dark-text">Contact Information</h4>
                            <div class="space-y-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">PIC Name</dt>
                                    <dd class="text-sm col-span-2">{{ $partner->pic_name }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Email</dt>
                                    <dd class="text-sm col-span-2">{{ $partner->email }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Phone Number</dt>
                                    <dd class="text-sm col-span-2">{{ $partner->phone_number }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Address</dt>
                                    <dd class="text-sm col-span-2">{!! nl2br(e($partner->address)) !!}</dd>
                                </div>
                            </div>
                        </div>

                        {{-- Stats Column --}}
                        <div class="md:col-span-1 space-y-4">
                            <h4 class="text-lg font-semibold text-light-text dark:text-dark-text">Activity Summary</h4>
                            <div class="space-y-4">
                                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                    <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Status</dt>
                                    <dd class="mt-1">
                                        @if($partner->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                    <dt class="text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Total Requests</dt>
                                    <dd class="text-2xl font-bold mt-1">{{ $partner->transactions->count() }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
