@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'disabled' => false, 'href' => null])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary' => 'bg-slate-900 text-white hover:bg-slate-800 focus:ring-slate-900',
    'secondary' => 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50 focus:ring-slate-500',
    'danger' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
    'success' => 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500',
    'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$class = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href && !$disabled)
<a href="{{ $href }}" class="{{ $class }}">
    {{ $slot }}
</a>
@elseif($href && $disabled)
<span class="{{ $class }}">
    {{ $slot }}
</span>
@else
<button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} class="{{ $class }}">
    {{ $slot }}
</button>
@endif
