@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-light-text-darker dark:text-dark-text-muted']) }}>
    {{ $value ?? $slot }}
</label>
