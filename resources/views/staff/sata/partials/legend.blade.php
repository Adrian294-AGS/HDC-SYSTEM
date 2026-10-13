{{-- Subtest legend, as printed under the table on the paper form. --}}
<dl class="mt-3 grid gap-x-6 gap-y-1 text-xs text-gray-600 dark:text-gray-400 sm:grid-cols-3">
    @foreach (\App\Models\EntranceTestResult::subtests() as $subtest)
        <div><dt class="inline font-semibold text-gray-700 dark:text-gray-300">{{ $subtest['code'] }}</dt> <dd class="inline">= {{ $subtest['name'] }}</dd></div>
    @endforeach
</dl>
