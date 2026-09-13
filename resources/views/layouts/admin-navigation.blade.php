<nav class="sticky top-0 z-20 bg-gray-900 border-b-[3px] border-brand-500">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <x-application-logo class="w-8 h-8 text-white" />
                    <span class="font-bold text-white hidden sm:block">{{ config('app.name') }} <span class="text-gray-400 font-normal">Admin</span></span>
                </a>

                <div class="hidden sm:flex sm:ml-8 sm:space-x-2 sm:items-center">
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.employees.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.employees.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        Employees
                    </a>
                    <a href="{{ route('admin.leave.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.leave.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        Leave Requests
                    </a>
                    <a href="{{ route('admin.borrow.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.borrow.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        Borrow Requests
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <span class="text-sm text-gray-300">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-gray-400 hover:text-white">Log Out</button>
                </form>
            </div>

            <div class="flex items-center sm:hidden">
                <button id="admin-mobile-menu-toggle" type="button" class="p-2 rounded-md text-gray-300 hover:bg-gray-800" aria-label="Toggle navigation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="admin-mobile-menu" class="hidden sm:hidden border-t border-gray-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800' }}">Dashboard</a>
            <a href="{{ route('admin.employees.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.employees.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800' }}">Employees</a>
            <a href="{{ route('admin.leave.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.leave.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800' }}">Leave Requests</a>
            <a href="{{ route('admin.borrow.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.borrow.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800' }}">Borrow Requests</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-400 hover:bg-gray-800">Log Out</button>
            </form>
        </div>
    </div>
</nav>
