@php use App\Support\Form5; @endphp

<div class="grid gap-6 sm:grid-cols-2">
    <x-form.select name="month_of_entry" label="Month of entry" :options="Form5::MONTHS" :value="$record->month_of_entry" required />
    <x-form.text name="school_year" label="School year" :value="$record->school_year" placeholder="2026-2027" required />
</div>

<x-form.radios name="semester" label="Semester" :options="Form5::SEMESTERS" :value="$record->semester" required />

<x-form.radios name="student_type" label="Student type" :options="Form5::STUDENT_TYPES" :value="$record->student_type" required />

<div>
    <x-input-label for="photo" :value="__('Photo (optional, 2x2)')" />
    @if ($record->photo_path)
        <div class="mt-2 flex items-center gap-4">
            <img src="{{ route('student.record.photo', $record) }}" alt="{{ __('Your 2x2 photo') }}" class="h-24 w-24 rounded border object-cover">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="remove_photo" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                {{ __('Remove photo') }}
            </label>
        </div>
    @endif
    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
        class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
    <p class="mt-1 text-xs text-gray-500">{{ __('JPG, PNG or WEBP, up to 2 MB.') }}</p>
    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
</div>
