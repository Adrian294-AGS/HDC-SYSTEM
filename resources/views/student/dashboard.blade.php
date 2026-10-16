@php use App\Enums\RecordStatus; @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <x-welcome-card>
        @if (Auth::user()->program)
            {{ Auth::user()->program->code }} – {{ Auth::user()->program->college->name }},
            {{ __('Year :level', ['level' => Auth::user()->year_level]) }}
            · {{ __('Student No.') }} {{ Auth::user()->student_number }}
        @endif
    </x-welcome-card>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel overflow-hidden sm:rounded-lg p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('HDC Form 5: Student Cumulative Record') }}</h3>
                        @if ($record)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ \App\Support\Form5::SEMESTERS[$record->semester] ?? '' }}, {{ __('S.Y.') }} {{ $record->school_year }}
                            </p>
                        @else
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Please fill out your record. You can save and come back any time.') }}</p>
                        @endif
                    </div>
                    <x-record-status :status="$record?->status" />
                </div>

                @php
                    $percent = $record?->progressPercent() ?? 0;
                @endphp
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>{{ __('Progress') }}</span>
                        <span>{{ $percent }}%</span>
                    </div>
                    <x-progress-bar :percent="$percent" class="mt-1" />
                </div>

                <div class="mt-6">
                    @if (! $record)
                        <a href="{{ route('student.record.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-100 dark:text-gray-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            {{ __('Start my record') }}
                        </a>
                    @elseif ($record->status === RecordStatus::Draft)
                        <a href="{{ route('student.record.edit', [$record, $record->nextIncompleteStep()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-100 dark:text-gray-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            {{ __('Continue my record') }}
                        </a>
                    @else
                        <a href="{{ route('student.record.show', $record) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-100 dark:text-gray-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            {{ __('View my record') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
