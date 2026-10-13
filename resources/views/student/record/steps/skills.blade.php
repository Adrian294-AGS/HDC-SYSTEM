@php
    use App\Support\Form5;
    use App\Support\FormName;
@endphp

<div x-data="{ skills: @js(FormName::current('skills_to_develop', $record->skills_to_develop ?? [])) }" class="space-y-3">
    <x-form.checkboxes name="skills_to_develop" label="Skills you want to develop through seminar-workshops" :options="Form5::SKILLS" :value="$record->skills_to_develop"
        model="skills" hint="Check all that apply." />
    <x-form.text name="skills_other" label="Others (specify)" :value="$record->skills_other" required
        x-show="skills.includes('others')" x-cloak />
</div>

<div class="space-y-4 border-t border-gray-100 dark:border-gray-700 pt-6">
    <x-form.yes-no name="willing_to_see_counselor"
        label="Would you be willing to talk with or seek help from a counselor any time regarding your concerns?"
        :value="$record->willing_to_see_counselor" />
    <x-form.text name="counselor_willingness_reason" label="Why?" :value="$record->counselor_willingness_reason" />
</div>
