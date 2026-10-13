@php
    use App\Support\Form5;
    use App\Support\FormName;

    $schoolRows = $record->schools->map->only(['level', 'school_name', 'place', 'inclusive_years', 'grade_average'])->all();

    if (! $record->hasCompletedStep('education') && $schoolRows === []) {
        $schoolRows = collect(Form5::SCHOOL_LEVELS)
            ->keys()
            ->map(fn ($level) => ['level' => $level, 'school_name' => '', 'place' => '', 'inclusive_years' => '', 'grade_average' => ''])
            ->all();
    }
@endphp

<div x-data="{ schoolingNature: @js(FormName::current('schooling_nature', $record->schooling_nature)) }" class="space-y-6">
    <x-form.repeater name="schools" label="Schools attended" add-label="Add school" :max="Form5::MAX_SCHOOLS"
        :rows="$schoolRows"
        :columns="[
            'level' => ['label' => 'Level', 'type' => 'select', 'options' => Form5::SCHOOL_LEVELS],
            'school_name' => ['label' => 'Name of school'],
            'place' => ['label' => 'Place'],
            'inclusive_years' => ['label' => 'Inclusive years (e.g. 2014-2020)'],
            'grade_average' => ['label' => 'Grade average'],
        ]" />

    <x-form.text name="scholarships_honors" label="Scholarships and honors received" :value="$record->scholarships_honors" />

    <div class="space-y-3">
        <x-form.radios name="schooling_nature" label="Nature of schooling" :options="Form5::SCHOOLING_NATURES" :value="$record->schooling_nature" required model="schoolingNature" />
        <x-form.textarea name="interruption_reason" label="If interrupted, why?" :value="$record->interruption_reason" required
            x-show="schoolingNature === 'interrupted'" x-cloak />
    </div>

    <x-form.text name="scholarship_to_apply" label="Scholarship you wish to apply for" :value="$record->scholarship_to_apply" />

    <div class="grid gap-6 sm:grid-cols-2">
        <x-form.text name="difficult_subjects" label="Subject(s) you have difficulty with" :value="$record->difficult_subjects" />
        <x-form.text name="best_liked_subjects" label="Best-liked subject(s)" :value="$record->best_liked_subjects" />
    </div>

    <x-form.checkboxes name="home_facilities" label="Educational facilities at home" :options="Form5::HOME_FACILITIES" :value="$record->home_facilities" :columns="3"
        hint="Check all that apply." />

    <x-form.text name="recent_book" label="Title of a book you finished reading recently" :value="$record->recent_book" />
</div>
