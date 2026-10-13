@php use App\Support\Form5; @endphp

<x-form.repeater name="organizations" label="Club/organization memberships" add-label="Add organization" :max="Form5::MAX_ORGANIZATIONS"
    :rows="$record->organizations->map->only(['organization', 'activity'])->all()"
    :columns="[
        'organization' => ['label' => 'Organization'],
        'activity' => ['label' => 'Activity participated in'],
    ]" />

<x-form.text name="hobbies" label="Leisure-time activities/hobbies" :value="$record->hobbies" />
