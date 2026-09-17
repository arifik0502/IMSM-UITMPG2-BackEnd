<x-admin-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="card">
            <p class="text-sm text-gray-500">Employees</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_employees'] }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-500">Clocked In Today</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['clocked_in_today'] }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-500">Pending Leave</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_leave'] }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-500">Pending Borrow</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_borrow'] }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-500">Overdue Equipment</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['overdue_borrow'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Pending Leave Requests</h2>
                <a href="{{ route('admin.leave.index') }}" class="text-sm text-brand-600 hover:underline">View all &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentLeave as $leave)
                    <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800">{{ $leave->borrowerName() }} @if($leave->isGuest())<span class="badge-gray ml-1">Guest</span>@endif</p>
                            <p class="text-gray-500 capitalize">{{ $leave->type }} &middot; {{ $leave->start_date->format('d M') }}&ndash;{{ $leave->end_date->format('d M Y') }}</p>
                        </div>
                        <span class="badge-yellow">Pending</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No pending leave requests.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Pending Borrow Requests</h2>
                <a href="{{ route('admin.borrow.index') }}" class="text-sm text-brand-600 hover:underline">View all &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentBorrow as $borrow)
                    <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800">{{ $borrow->borrowerName() }} @if($borrow->isGuest())<span class="badge-gray ml-1">Guest</span>@endif</p>
                            <p class="text-gray-500">{{ $borrow->equipment->code }} &middot; {{ $borrow->borrow_date->format('d M') }}&ndash;{{ $borrow->return_date->format('d M Y') }}</p>
                        </div>
                        <span class="badge-yellow">Pending</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No pending borrow requests.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
