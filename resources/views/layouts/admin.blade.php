<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($title ?? '') ? $title.' - Admin' : 'Admin' }} &middot; {{ config('app.name', 'Attendance System') }}</title>

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
    @include('layouts.admin-navigation')

    <header class="page-header">
        <div class="app-container">
            <h1 class="page-title">{{ $header ?? '' }}</h1>
        </div>
    </header>

    <main class="app-main">
        @if (session('success'))
            <div class="alert-success mb-6" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error mb-6" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <script>
        document.getElementById('admin-mobile-menu-toggle')?.addEventListener('click', function () {
            document.getElementById('admin-mobile-menu')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
