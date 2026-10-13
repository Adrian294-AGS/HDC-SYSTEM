<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
</x-app-layout>
