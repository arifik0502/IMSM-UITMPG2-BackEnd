<nav class="app-nav">
    <div class="app-container">
        <div class="app-nav-bar">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="brand">
                    <x-application-logo class="brand-mark w-8 h-8" />
                    <span class="brand-name hidden sm:block">{{ config('app.name') }}</span>
                </a>

                <div class="hidden lg:flex lg:items-center lg:gap-1">
                    <a href="{{ route('dashboard') }}" class="nav-link" @if (request()->routeIs('dashboard')) aria-current="page" @endif>Dashboard</a>
                    <a href="{{ route('attendance.history') }}" class="nav-link" @if (request()->routeIs('attendance.history')) aria-current="page" @endif>Attendance History</a>
                    <a href="{{ route('leave.index') }}" class="nav-link" @if (request()->routeIs('leave.index')) aria-current="page" @endif>Leave</a>
                    <a href="{{ route('borrow.create') }}" class="nav-link" @if (request()->routeIs('borrow.*')) aria-current="page" @endif>Borrow Equipment</a>
                    <a href="{{ route('chat.index') }}" class="nav-link" @if (request()->routeIs('chat.*')) aria-current="page" @endif>
                        Chat
                        <span class="chat-unread-badge nav-badge hidden">0</span>
                    </a>
                </div>
            </div>

            <div class="hidden lg:flex lg:items-center lg:gap-1">
                <button type="button" data-theme-toggle class="icon-btn" aria-label="Toggle color theme">
                    <svg data-theme-icon-sun class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36 6.36-1.42-1.42M7.05 7.05 5.64 5.64m12.72 0-1.41 1.41M7.05 16.95l-1.41 1.41"/>
                        <circle cx="12" cy="12" r="4"/>
                    </svg>
                    <svg data-theme-icon-moon class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/>
                    </svg>
                </button>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link nav-link-accent">Admin Panel</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="nav-link" @if (request()->routeIs('profile.*')) aria-current="page" @endif>{{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link nav-link-danger">Log Out</button>
                </form>
            </div>

            <div class="flex items-center lg:hidden">
                <button id="mobile-menu-toggle" type="button" class="icon-btn" aria-label="Toggle navigation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden">
        <div class="app-container">
            <div class="mobile-menu">
                <a href="{{ route('dashboard') }}" class="mobile-link" @if (request()->routeIs('dashboard')) aria-current="page" @endif>Dashboard</a>
                <a href="{{ route('attendance.history') }}" class="mobile-link" @if (request()->routeIs('attendance.history')) aria-current="page" @endif>Attendance History</a>
                <a href="{{ route('leave.index') }}" class="mobile-link" @if (request()->routeIs('leave.index')) aria-current="page" @endif>Leave</a>
                <a href="{{ route('borrow.create') }}" class="mobile-link" @if (request()->routeIs('borrow.*')) aria-current="page" @endif>Borrow Equipment</a>
                <a href="{{ route('chat.index') }}" class="mobile-link" @if (request()->routeIs('chat.*')) aria-current="page" @endif>
                    Chat
                    <span class="chat-unread-badge nav-badge-inline hidden">0</span>
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="mobile-link">Admin Panel</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="mobile-link" @if (request()->routeIs('profile.*')) aria-current="page" @endif>Profile ({{ auth()->user()->name }})</a>
                <div class="flex items-center justify-between px-3 py-2">
                    <span class="text-[15px] font-medium text-muted">Theme</span>
                    <button type="button" data-theme-toggle class="icon-btn" aria-label="Toggle color theme">
                    <svg data-theme-icon-sun class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36 6.36-1.42-1.42M7.05 7.05 5.64 5.64m12.72 0-1.41 1.41M7.05 16.95l-1.41 1.41"/>
                        <circle cx="12" cy="12" r="4"/>
                    </svg>
                    <svg data-theme-icon-moon class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/>
                    </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-link mobile-link-danger">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
