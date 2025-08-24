@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 bg-white dark:bg-dark-surface text-light-text-darker dark:text-dark-text focus:border-accent focus:ring-accent rounded-md shadow-sm']) !!}>
