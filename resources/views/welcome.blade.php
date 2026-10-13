<x-guest-layout>
    <div class="py-4 text-center">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Student Cumulative Record System') }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Human Development Center, University of La Salette, Santiago City') }}
        </p>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-100 dark:text-gray-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    {{ __('Go to dashboard') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-100 dark:text-gray-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    {{ __('Log in') }}
                </a>
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700/60">
                    {{ __('Register as a student') }}
                </a>
            @endauth
        </div>
    </div>
</x-guest-layout>
