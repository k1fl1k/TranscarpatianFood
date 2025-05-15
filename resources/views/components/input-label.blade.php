@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-amber-900 dark:text-amber-400']) }}>
    {{ $value ?? $slot }}
</label>
