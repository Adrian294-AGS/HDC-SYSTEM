@php($user = Auth::user())

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <p class="text-xl font-semibold">{{ __('Welcome, :name', ['name' => $user->name]) }}</p>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('Role:') }}
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">{{ $user->role->label() }}</span>
                </p>

                @if ($slot->isNotEmpty())
                    <div class="mt-4 text-sm text-gray-600">{{ $slot }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
