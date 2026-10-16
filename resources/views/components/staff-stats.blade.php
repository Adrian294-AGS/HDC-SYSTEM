@props(['stats'])

@php
    $tiles = [
        ['label' => __('Total students'), 'value' => $stats['total'], 'href' => route('staff.records.index')],
        ['label' => __('Submitted'), 'value' => $stats['submitted'], 'href' => route('staff.records.index', ['status' => 'submitted'])],
        ['label' => __('Not yet submitted'), 'value' => $stats['not_submitted'], 'href' => route('staff.tracker')],
        ['label' => __('Waiting for review'), 'value' => $stats['waiting_for_review'], 'href' => route('staff.records.index', ['status' => 'submitted'])],
    ];
@endphp

<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
            @foreach ($tiles as $tile)
                <a href="{{ $tile['href'] }}" class="block glass-panel rounded-lg p-5 ring-1 ring-transparent transition hover:ring-indigo-300 dark:hover:ring-indigo-500/60">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $tile['label'] }}</p>
                    <p class="mt-1 text-3xl font-semibold tabular-nums text-gray-900 dark:text-gray-100">{{ number_format($tile['value']) }}</p>
                </a>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('staff.records.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-100 dark:text-gray-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                {{ __('Open records list') }}
            </a>
            <a href="{{ route('staff.tracker') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700/60">
                {{ __('Open submission tracker') }}
            </a>
        </div>
    </div>
</div>
