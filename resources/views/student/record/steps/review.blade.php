@php use App\Support\Form5; @endphp

<p class="mt-2 text-sm text-gray-600">
    {{ __('Please check your answers. Use "Edit" on any section to change it.') }}
</p>

@error('record')
    <div class="mt-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">{{ $message }}</div>
@enderror

<div class="mt-6">
    @include('student.record.summary', ['editable' => true])
</div>

<form method="POST" action="{{ route('student.record.submit', $record) }}" class="mt-8 space-y-4 border-t border-gray-200 pt-6">
    @csrf

    <h4 class="text-base font-semibold text-gray-900">{{ __('Data privacy consent') }}</h4>

    <label class="flex items-start gap-3 text-sm text-gray-800">
        <input type="checkbox" name="consent" value="1" class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
            @checked(old('consent', $record->consent_given))>
        <span>{{ Form5::CONSENT_TEXT }}</span>
    </label>
    <x-input-error :messages="$errors->get('consent')" />

    <div class="max-w-md">
        <x-form.text name="consent_name" label="Signature over printed name (type your full name)" :value="$record->consent_name" required autocomplete="name" />
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
        <a href="{{ route('student.record.edit', [$record, Form5::previousStep('review')]) }}" class="text-sm text-gray-600 underline hover:text-gray-900">
            &larr; {{ __('Back') }}
        </a>
        <x-primary-button>
            {{ $record->submitted_at ? __('Update submission') : __('Submit my record') }}
        </x-primary-button>
    </div>
</form>
