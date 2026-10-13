@php
    use App\Support\Form5;
    use App\Support\FormName;
@endphp

<div x-data="{ selected: @js(FormName::current('self_description', $record->self_description ?? [])) }" class="space-y-3">
    <x-form.checkboxes name="self_description" label="Please check at least five" :options="Form5::SELF_DESCRIPTIONS" :value="$record->self_description"
        required model="selected" :columns="3" />

    <p class="text-sm" :class="selected.length >= {{ Form5::MIN_SELF_DESCRIPTIONS }} ? 'text-green-700' : 'text-gray-600'" aria-live="polite">
        <span x-text="selected.length"></span> {{ __('checked') }}
        <span x-show="selected.length < {{ Form5::MIN_SELF_DESCRIPTIONS }}">({{ __('at least :n needed', ['n' => Form5::MIN_SELF_DESCRIPTIONS]) }})</span>
    </p>
</div>
