<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
        @if(isset($header))
        <thead class="bg-slate-50">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        @endif
        <tbody class="bg-white divide-y divide-slate-200">
            {{ $slot }}
        </tbody>
        @if(isset($footer))
        <tfoot class="bg-slate-50">
            <tr>
                {{ $footer }}
            </tr>
        </tfoot>
        @endif
    </table>
</div>
