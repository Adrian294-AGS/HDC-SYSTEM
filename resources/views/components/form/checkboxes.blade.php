{{-- Multi-select checklist stored as a JSON array. Pass `model` to bind the checked keys to an Alpine array. --}}
@props(['name', 'label', 'options', 'value' => [], 'required' => false, 'model' => null, 'columns' => 2, 'hint' => null])

@php
    $checked = array_map('strval', (array) (session()->hasOldInput() ? old($name, []) : ($value ?? [])));
    $gridColumns = ['sm:grid-cols-1', 'sm:grid-cols-2', 'sm:grid-cols-3'][$columns - 1] ?? 'sm:grid-cols-2';
@endphp

<fieldset {{ $attributes->only(['class', 'x-show', 'x-cloak']) }}>
    <legend class="block font-medium text-sm text-gray-700">
        {{ $label }} @if ($required)<span class="text-red-600" aria-hidden="true">*</span>@endif
    </legend>
    @if ($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    <div class="mt-2 grid grid-cols-1 {{ $gridColumns }} gap-x-6 gap-y-2">
        @foreach ($options as $optionValue => $optionLabel)
            <label class="inline-flex items-start gap-2 text-sm text-gray-800">
                <input type="checkbox" name="{{ \App\Support\FormName::html($name) }}[]" value="{{ $optionValue }}"
                    class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    @checked(in_array((string) $optionValue, $checked, true))
                    @if ($model) x-model="{{ $model }}" @endif>
                <span>{{ $optionLabel }}</span>
            </label>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</fieldset>
