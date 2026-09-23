/**
 * Manual light/dark theme toggle. Persists the choice in localStorage;
 * falls back to the OS `prefers-color-scheme` when nothing is stored yet.
 * The actual colors live in resources/css/app.css as CSS variables driven
 * by the `data-theme` attribute on <html>.
 */
const STORAGE_KEY = 'isms_theme';

function storedTheme() {
    try {
        return localStorage.getItem(STORAGE_KEY);
    } catch (e) {
        return null;
    }
}

function effectiveTheme() {
    const stored = storedTheme();
    if (stored === 'light' || stored === 'dark') return stored;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function syncIcons() {
    const dark = effectiveTheme() === 'dark';
    document.querySelectorAll('[data-theme-icon-sun]').forEach((el) => el.classList.toggle('hidden', !dark));
    document.querySelectorAll('[data-theme-icon-moon]').forEach((el) => el.classList.toggle('hidden', dark));
}

// Keep the attribute in sync with storage on load (the inline <head>
// script already set it before paint — this just wires up the icons).
syncIcons();

document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const next = effectiveTheme() === 'dark' ? 'light' : 'dark';
        try {
            localStorage.setItem(STORAGE_KEY, next);
        } catch (e) {
            // private browsing etc. — theme just won't persist
        }
        document.documentElement.setAttribute('data-theme', next);
        syncIcons();
    });
});
