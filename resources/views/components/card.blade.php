@props(['title' => null, 'actions' => null])

<div class="bg-white rounded-lg shadow-sm border border-slate-200">
    @if($title || $actions)
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        @if($title)
        <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
        @endif
        @if($actions)
        <div class="flex items-center space-x-2">
            {{ $actions }}
        </div>
        @endif
    </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
