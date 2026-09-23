<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col items-center pt-8 sm:pt-0 sm:justify-center">
        <div class="mb-6 flex items-center gap-2 text-brand-600">
            <x-application-logo class="w-10 h-10" />
            <span class="text-xl font-bold text-gray-800">{{ config('app.name', 'Attendance System') }}</span>
        </div>

        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-sm rounded-xl border border-gray-200">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
