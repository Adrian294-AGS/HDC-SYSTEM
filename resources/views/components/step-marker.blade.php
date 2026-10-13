@props(['number', 'done' => false, 'current' => false])

<span @class([
    'flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-semibold',
    'bg-green-600 text-white' => $done,
    'bg-indigo-600 text-white' => ! $done && $current,
    'border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400' => ! $done && ! $current,
])>
    @if ($done)
        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4a1 1 0 111.4-1.4L8 12.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
        <span class="sr-only">{{ __('completed') }}</span>
    @else
        {{ $number }}
    @endif
</span>
