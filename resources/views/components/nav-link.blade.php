@props(['active'])

@php
$classes = ($active ?? false)
            // Kelas untuk link AKTIF
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accent text-accent font-semibold text-sm focus:outline-none transition duration-150 ease-in-out uppercase'
            // Kelas untuk link TIDAK AKTIF
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-light-header-text dark:text-dark-text-muted hover:text-white dark:hover:text-dark-text hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-white dark:focus:text-dark-text focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out uppercase';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
