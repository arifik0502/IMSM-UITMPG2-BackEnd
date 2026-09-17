<nav class="sticky top-0 z-20 bg-white border-b-[3px] border-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <x-application-logo class="w-8 h-8 text-brand-600" />
                    <span class="font-bold text-gray-800 hidden sm:block">{{ config('app.name') }}</span>
                </a>

                <div class="hidden sm:flex sm:ml-8 sm:space-x-4 sm:items-center">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('attendance.history') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('attendance.history') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Attendance History
                    </a>
                    <a href="{{ route('leave.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('leave.index') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Leave
                    </a>
                    <a href="{{ route('borrow.create') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('borrow.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Borrow Equipment
                    </a>
                    <a href="{{ route('chat.index') }}"
                       class="relative px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('chat.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Chat
                        <span class="chat-unread-badge hidden absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[1.1rem] h-[1.1rem] px-1 rounded-full bg-red-500 text-white text-[10px] font-semibold">0</span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-brand-600 hover:underline">Admin Panel</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    {{ auth()->user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-600">Log Out</button>
                </form>
            </div>

            <div class="flex items-center sm:hidden">
                <button id="mobile-menu-toggle" type="button" class="p-2 rounded-md text-gray-500 hover:bg-gray-100" aria-label="Toggle navigation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden sm:hidden border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard</a>
            <a href="{{ route('attendance.history') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('attendance.history') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">Attendance History</a>
            <a href="{{ route('leave.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('leave.index') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">Leave</a>
            <a href="{{ route('borrow.create') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('borrow.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">Borrow Equipment</a>
            <a href="{{ route('chat.index') }}" class="relative block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('chat.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">
                Chat
                <span class="chat-unread-badge hidden ml-2 inline-flex items-center justify-center min-w-[1.1rem] h-[1.1rem] px-1 rounded-full bg-red-500 text-white text-[10px] font-semibold">0</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50">Profile ({{ auth()->user()->name }})</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-gray-50">Log Out</button>
            </form>
        </div>
    </div>
</nav>
