<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Attendance System') }}</title>

    <script>
        (function () {
            try {
                var t = localStorage.getItem('isms_theme');
                if (t === 'light' || t === 'dark') document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body font-sans antialiased">
    <div class="auth-shell">
        <div class="brand mb-6">
            <x-application-logo class="brand-mark w-10 h-10" />
            <span class="brand-name text-xl">{{ config('app.name', 'Attendance System') }}</span>
        </div>

        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
