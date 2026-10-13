{{-- Runs before first paint: applies the saved theme (or the device setting) so there is no white flash. --}}
<script>
    (function () {
        var dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        try {
            var saved = localStorage.getItem('theme');
            if (saved === 'dark' || saved === 'light') {
                dark = saved === 'dark';
            }
        } catch (e) {}
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
    })();
</script>
