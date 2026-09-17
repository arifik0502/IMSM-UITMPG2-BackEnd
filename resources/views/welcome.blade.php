<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IMSM APP') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center gap-3 mb-10">
            <x-application-logo class="w-10 h-10 text-brand-600" />
            <span class="text-2xl font-bold text-gray-800">{{ config('app.name') }}</span>
        </div>

        @if (session('success'))
            <div class="mb-8 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
                
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome</h1>
        <p class="text-gray-600 mb-10">Employee attendance, leave, and equipment borrowing in one place.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="card flex flex-col">
                <h2 class="font-semibold text-gray-800 mb-2">Employee</h2>
                <p class="text-sm text-gray-500 mb-6 flex-1">Clock in/out, track attendance, chat with colleagues, and manage your own leave and equipment bookings.</p>
                <a href="{{ route('login') }}" class="btn-primary justify-center">Employee Login</a>
                <a href="{{ route('register') }}" class="text-sm text-center text-brand-600 hover:underline mt-3">Create an account</a>
            </div>

            <div class="card flex flex-col">
                <h2 class="font-semibold text-gray-800 mb-2">Guest &mdash; Apply Leave</h2>
                <p class="text-sm text-gray-500 mb-6 flex-1">Not an employee account holder? Submit a leave application with just your name and email &mdash; no login needed.</p>
                <a href="{{ route('guest.leave.create') }}" class="btn-secondary justify-center">Apply for Leave</a>
            </div>

            <div class="card flex flex-col">
                <h2 class="font-semibold text-gray-800 mb-2">Borrow Equipment</h2>
                <p class="text-sm text-gray-500 mb-6 flex-1">Book a laptop, webcam, projector, or PA system. Open to both employees and guests.</p>
                <a href="{{ route('borrow.create') }}" class="btn-secondary justify-center">Borrow Equipment</a>
            </div>
        </div>
    </div>
</body>
</html>
