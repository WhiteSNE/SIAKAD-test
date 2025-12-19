@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-medium text-white bg-indigo-600 rounded-lg transition shadow-md'
            : 'flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>