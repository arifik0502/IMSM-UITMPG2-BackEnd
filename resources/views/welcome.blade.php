<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IMSM APP') }}</title>

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
    <div class="app-container max-w-5xl py-12 sm:py-16">
        <div class="brand mb-10">
            <x-application-logo class="brand-mark w-10 h-10" />
            <span class="brand-name text-2xl">{{ config('app.name') }}</span>
        </div>

        @if (session('success'))
            <div class="alert-success mb-6" role="status">
                {{ session('success') }}
            </div>
        @endif
                
        <h1 class="text-3xl font-semibold tracking-tight mb-2">Welcome</h1>
        <p class="text-muted mb-10">Employee attendance, leave, and equipment borrowing in one place.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card flex flex-col">
                <h2 class="section-title mb-2">Employee</h2>
                <p class="text-sm text-muted mb-6 flex-1">Clock in/out, track attendance, chat with colleagues, and manage your own leave and equipment bookings.</p>
                <a href="{{ route('login') }}" class="btn-primary">Employee Login</a>
                <a href="{{ route('register') }}" class="link link-accent mt-4 self-center">Create an account</a>
            </div>

            <div class="card flex flex-col">
                <h2 class="section-title mb-2">Guest &mdash; Apply Leave</h2>
                <p class="text-sm text-muted mb-6 flex-1">Not an employee account holder? Submit a leave application with just your name and email &mdash; no login needed.</p>
                <a href="{{ route('guest.leave.create') }}" class="btn-secondary">Apply for Leave</a>
            </div>

            <div class="card flex flex-col">
                <h2 class="section-title mb-2">Borrow Equipment</h2>
                <p class="text-sm text-muted mb-6 flex-1">Book a laptop, webcam, projector, or PA system. Open to both employees and guests.</p>
                <a href="{{ route('borrow.create') }}" class="btn-secondary">Borrow Equipment</a>
            </div>
        </div>
    </div>
</body>
</html>
