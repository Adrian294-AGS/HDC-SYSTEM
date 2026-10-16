import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Follow the device's light/dark setting live, until the user picks a theme with the toggle.
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
    let saved = null;
    try {
        saved = localStorage.getItem('theme');
    } catch (e) {}

    if (saved !== 'dark' && saved !== 'light') {
        window.applyTheme(event.matches, false);
    }
});

Alpine.start();
