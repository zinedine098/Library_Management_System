@props(['variant' => 'default', 'size' => 'md'])

@php
$classes = [
    'default' => 'bg-slate-100 text-slate-800',
    'primary' => 'bg-slate-900 text-white',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'danger' => 'bg-red-100 text-red-800',
    'info' => 'bg-blue-100 text-blue-800',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-0.5 text-sm',
    'lg' => 'px-3 py-1 text-base',
];

$class = $classes[$variant] ?? $classes['default'];
$size = $sizes[$size] ?? $sizes['md'];
@endphp

<span class="inline-flex items-center font-medium rounded-md {{ $class }} {{ $size }}">
    {{ $slot }}
</span>
