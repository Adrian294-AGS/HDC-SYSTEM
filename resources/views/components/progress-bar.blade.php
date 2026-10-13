@props(['percent' => 0])

<div {{ $attributes->merge(['class' => 'h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700']) }}
    role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $percent }}">
    <div class="h-full rounded-full bg-indigo-600 transition-all" style="width: {{ $percent }}%"></div>
</div>
