<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Counselor Dashboard') }}
        </h2>
    </x-slot>

    <x-welcome-card>
        {{ __('Assigned college(s):') }}
        {{ Auth::user()->colleges->pluck('name')->join(', ') ?: __('None yet. Ask the HDC Director to assign you.') }}
    </x-welcome-card>

    <x-staff-stats :stats="$stats" />
</x-app-layout>
