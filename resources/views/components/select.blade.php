@props(['label' => null, 'id' => null, 'name' => null, 'required' => false, 'options' => [], 'value' => null, 'disabled' => false, 'placeholder' => 'Select...'])

<div>
    @if($label)
    <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-slate-700 mb-1">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    <select id="{{ $id ?? $name }}" 
            name="{{ $name }}" 
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="w-full px-3 py-2 border border-slate-300 rounded-md text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent disabled:bg-slate-100 disabled:text-slate-500 @error($name ?? '') ring-1 ring-red-500 @enderror">
        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" {{ old($name ?? '', $value) == $optionValue ? 'selected' : '' }}>
            {{ $optionLabel }}
        </option>
        @endforeach
    </select>
    @error($name ?? '')
    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
