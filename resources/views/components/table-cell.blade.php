@props(['scope' => 'cell'])

@php
$classes = $scope === 'header' 
    ? 'px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'
    : 'px-6 py-4 whitespace-nowrap text-sm text-slate-900';
@endphp

<{{ $scope === 'header' ? 'th' : 'td' }} class="{{ $classes }}">
    {{ $slot }}
</{{ $scope === 'header' ? 'th' : 'td' }}>
