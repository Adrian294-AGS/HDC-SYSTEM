{{-- Read-only SATA result laid out like the paper form. Expects $result. --}}
@php
    $subtests = \App\Models\EntranceTestResult::subtests();
@endphp

<div class="overflow-x-auto rounded-md border border-gray-200 dark:border-gray-700">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/40">
            <tr>
                <th scope="col" class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-300"><span class="sr-only">{{ __('Row') }}</span></th>
                @foreach ($subtests as $subtest)
                    <th scope="col" class="px-3 py-2 text-center font-semibold text-gray-700 dark:text-gray-300" title="{{ $subtest['name'] }}">{{ $subtest['code'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr>
                <th scope="row" class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Standard Score') }}</th>
                @foreach (array_keys($subtests) as $key)
                    <td class="px-3 py-2 text-center tabular-nums text-gray-900 dark:text-gray-100">{{ $result->{$key.'_score'} }}</td>
                @endforeach
            </tr>
            <tr>
                <th scope="row" class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">{{ __('Descriptor') }}</th>
                @foreach (array_keys($subtests) as $key)
                    <td class="px-3 py-2 text-center text-xs text-gray-700 dark:text-gray-300">{{ $result->{$key.'_descriptor'} }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

@include('staff.sata.partials.legend')

<dl class="mt-4 grid gap-x-6 gap-y-3 text-sm sm:grid-cols-3">
    <div>
        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Quotient') }}</dt>
        <dd class="text-gray-900 dark:text-gray-100"><span class="text-lg font-semibold tabular-nums">{{ $result->quotient }}</span> · {{ $result->quotient_descriptor }}</dd>
    </div>
    <div>
        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Date of examination') }}</dt>
        <dd class="text-gray-900 dark:text-gray-100">{{ $result->exam_date->format('F j, Y') }}</dd>
    </div>
    <div>
        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Encoded / last updated') }}</dt>
        <dd class="text-gray-900 dark:text-gray-100">
            {{ __('By :name on :date', ['name' => $result->encoder?->name ?? __('(removed user)'), 'date' => $result->created_at->format('M j, Y')]) }}
            @if ($result->updated_at->gt($result->created_at))
                <br><span class="text-gray-600 dark:text-gray-400">{{ __('Updated by :name on :date', ['name' => $result->updater?->name ?? __('(removed user)'), 'date' => $result->updated_at->format('M j, Y g:i A')]) }}</span>
            @endif
        </dd>
    </div>
    @if ($result->remarks)
        <div class="sm:col-span-3">
            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Remarks') }}</dt>
            <dd class="whitespace-pre-line text-gray-900 dark:text-gray-100">{{ $result->remarks }}</dd>
        </div>
    @endif
</dl>
