@props(['label' => null, 'id' => null, 'name' => null, 'type' => 'text', 'required' => false, 'placeholder' => null, 'value' => null, 'disabled' => false, 'readonly' => false])

<div>
    @if($label)
    <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-slate-700 mb-1">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    <input type="{{ $type }}" 
           id="{{ $id ?? $name }}" 
           name="{{ $name }}" 
           value="{{ old($name, $value) }}"
           {{ $required ? 'required' : '' }}
           {{ $disabled ? 'disabled' : '' }}
           {{ $readonly ? 'readonly' : '' }}
           placeholder="{{ $placeholder }}"
           class="w-full px-3 py-2 border border-slate-300 rounded-md text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent disabled:bg-slate-100 disabled:text-slate-500 @error($name ?? '') ring-1 ring-red-500 @enderror">
    @error($name ?? '')
    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
