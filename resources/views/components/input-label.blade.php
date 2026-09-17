@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold uppercase tracking-wide text-gray-900 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>