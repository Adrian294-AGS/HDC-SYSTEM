<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Counselor Dashboard') }}
        </h2>
    </x-slot>

    <x-welcome-card>
        {{ __('Assigned college(s):') }}
        {{ Auth::user()->colleges->pluck('name')->join(', ') ?: __('None yet. Ask the HDC Director to assign you.') }}
    </x-welcome-card>
</x-app-layout>
