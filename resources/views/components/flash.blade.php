@if (session('status'))
    <div class="rounded-md bg-green-50 dark:bg-green-500/10 px-4 py-3 text-sm text-green-800 dark:text-green-300" role="status">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="rounded-md bg-red-50 dark:bg-red-500/10 px-4 py-3 text-sm text-red-800 dark:text-red-300" role="alert">{{ session('error') }}</div>
@endif
