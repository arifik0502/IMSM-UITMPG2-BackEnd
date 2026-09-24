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
    <header class="page-header">
        <div class="app-container">
            <div class="brand">
                <x-application-logo class="brand-mark w-8 h-8" />
                <span class="brand-name">{{ config('app.name') }}</span>
            </div>
        </div>
    </header>

    <main class="app-main">
        {{ $slot }}
    </main>
</body>
</html>
