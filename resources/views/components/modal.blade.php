@props(['title', 'name' => 'modal', 'show' => false, 'maxWidth' => 'lg'])

@php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
];

$maxWidth = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['lg'];
@endphp

<div x-data="{ 
    showModal: @js($show),
    focusables() {
        return this.$el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex=\"-1\"])')
    },
    firstFocusable() {
        return this.focusables()[0]
    },
    lastFocusable() {
        return this.focusables().slice(-1)[0]
    },
    open() {
        this.showModal = true
        document.body.classList.add('overflow-hidden')
        this.$nextTick(() => {
            this.firstFocusable()?.focus()
        })
    },
    close() {
        this.showModal = false
        document.body.classList.remove('overflow-hidden')
        @this?.dispatch('modal-closed')
    }
}" 
x-init="() => {
    @if($show) open()
    @endif
    $watch('showModal', value => {
        if (value) {
            document.body.classList.add('overflow-hidden')
        } else {
            document.body.classList.remove('overflow-hidden')
        }
    })
}"
x-show="showModal"
x-cloak
@modal-closed.window="showModal = false"
class="fixed inset-0 z-50 overflow-y-auto" 
style="display: {{ $show ? 'block' : 'none' }};" 
aria-labelledby="modal-title" 
role="dialog" 
aria-modal="true">
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" 
             aria-hidden="true"
             @click="close()">
        </div>

        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full {{ $maxWidth }} p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg"
             @keydown.escape.window="close()"
             @keydown.tab.prevent="$event.shiftKey || lastFocusable().focus()"
             @keydown.shift.tab.prevent="firstFocusable().focus()">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900" id="modal-title">
                    {{ $title }}
                </h3>
                <button @click="close()" type="button" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
