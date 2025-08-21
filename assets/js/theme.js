(function () {
    function getStoredTheme() {
        try {
            return localStorage.getItem('theme');
        } catch (e) {
            return null;
        }
    }

    function storeTheme(theme) {
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {
            // ignore
        }
    }

    function getPreferredTheme() {
        var stored = getStoredTheme();
        if (stored === 'light' || stored === 'dark') return stored;
        var prefersDark = false;
        try {
            prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        } catch (e) { }
        return prefersDark ? 'dark' : 'light';
    }

    function updateToggleIcons(theme) {
        var icons = document.querySelectorAll('[data-theme-toggle-icon]');
        icons.forEach(function (icon) {
            icon.classList.remove('bi-sun', 'bi-moon');
            // Show sun when dark (to indicate clicking will go to light), moon when light
            icon.classList.add(theme === 'dark' ? 'bi-sun' : 'bi-moon');
            icon.setAttribute('aria-label', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
            icon.setAttribute('title', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
        });
    }

    function applyTheme(theme) {
        var t = theme === 'dark' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', t);
        try {
            document.documentElement.style.colorScheme = t;
        } catch (e) { }
        updateToggleIcons(t);
        storeTheme(t);
    }

    function toggleTheme() {
        var current = document.documentElement.getAttribute('data-theme') || getPreferredTheme();
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize theme based on stored/system preference
        applyTheme(getPreferredTheme());

        // Wire up toggle buttons
        var toggles = document.querySelectorAll('#themeToggle, .theme-toggle');
        toggles.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                toggleTheme();
            });
        });

        // Respond to system theme changes if user hasn't explicitly chosen
        try {
            var mql = window.matchMedia('(prefers-color-scheme: dark)');
            if (mql && mql.addEventListener) {
                mql.addEventListener('change', function (e) {
                    if (!getStoredTheme()) {
                        applyTheme(e.matches ? 'dark' : 'light');
                    }
                });
            }
        } catch (e) { }
    });
})();


