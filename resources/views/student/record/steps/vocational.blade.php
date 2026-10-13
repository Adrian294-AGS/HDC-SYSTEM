@php
    use App\Support\Form5;
    use App\Support\FormName;
@endphp

<div x-data="{
        courseReasons: @js(FormName::current('course_reasons', $record->course_reasons ?? [])),
        hasWork: @js(FormName::current('has_work_experience', $record->has_work_experience)),
    }" class="space-y-6">
    <div class="space-y-3">
        <x-form.checkboxes name="course_reasons" label="Reason in choosing your course" :options="Form5::COURSE_REASONS" :value="$record->course_reasons"
            required model="courseReasons" hint="Check all that apply." />
        <x-form.text name="course_reason_other" label="Others (specify)" :value="$record->course_reason_other" required
            x-show="courseReasons.includes('others')" x-cloak />
    </div>

    <x-form.text name="second_course" label="Second course alternative" :value="$record->second_course" />

    <x-form.textarea name="considered_occupation" label="Occupation/job seriously considered, and why" :value="$record->considered_occupation" />

    <div class="space-y-4">
        <x-form.yes-no name="has_work_experience" label="Work experience as a student?" :value="$record->has_work_experience" model="hasWork" />
        <div x-show="hasWork === '1'" x-cloak class="space-y-4 rounded-md bg-gray-50 p-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-form.text name="work_experience_when" label="When was this?" :value="$record->work_experience_when" required />
                <x-form.text name="work_experience_details" label="Specific work experience" :value="$record->work_experience_details" required />
            </div>
            <x-form.textarea name="work_experience_likes" label="What do you like about your work experience?" :value="$record->work_experience_likes" />
        </div>
    </div>
</div>
