<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Attendance System') }}</title>

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
<body class="font-sans antialiased bg-gray-50 min-h-screen" data-chat-unread-url="{{ route('chat.unread-count') }}">
    @include('layouts.navigation')

    <header class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="text-lg font-semibold text-gray-800">{{ $header ?? '' }}</h1>
        </div>
    </header>

    <main class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <script>
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function () {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
