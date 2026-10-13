<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Director Dashboard') }}
        </h2>
    </x-slot>

    <x-welcome-card>
        {{ __('You have access to all colleges.') }}
    </x-welcome-card>

    <x-staff-stats :stats="$stats" />
</x-app-layout>
