@props(['name', 'label', 'value' => null, 'required' => false, 'rows' => 3])

@php($id = str_replace('.', '_', $name))

<div {{ $attributes->only(['class', 'x-show', 'x-cloak']) }}>
    <x-input-label :for="$id">
        {{ $label }} @if ($required)<span class="text-red-600 dark:text-red-400" aria-hidden="true">*</span>@endif
    </x-input-label>
    <textarea id="{{ $id }}" name="{{ \App\Support\FormName::html($name) }}" rows="{{ $rows }}"
        class="mt-1 block w-full border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
        {{ $attributes->except(['class', 'x-show', 'x-cloak']) }}>{{ old($name, $value) }}</textarea>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
