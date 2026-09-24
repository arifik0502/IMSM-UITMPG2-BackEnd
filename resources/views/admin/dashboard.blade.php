<x-admin-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="card">
            <p class="stat-label">Employees</p>
            <p class="stat-value">{{ $stats['total_employees'] }}</p>
        </div>
        <div class="card">
            <p class="stat-label">Clocked In Today</p>
            <p class="stat-value">{{ $stats['clocked_in_today'] }}</p>
        </div>
        <div class="card">
            <p class="stat-label">Pending Leave</p>
            <p class="stat-value stat-value-warning">{{ $stats['pending_leave'] }}</p>
        </div>
        <div class="card">
            <p class="stat-label">Pending Borrow</p>
            <p class="stat-value stat-value-warning">{{ $stats['pending_borrow'] }}</p>
        </div>
        <div class="card">
            <p class="stat-label">Overdue Equipment</p>
            <p class="stat-value stat-value-error">{{ $stats['overdue_borrow'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="section-head">
                <h2 class="section-title">Pending Leave Requests</h2>
                <a href="{{ route('admin.leave.index') }}" class="link link-accent">View all &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentLeave as $leave)
                    <div class="list-row">
                        <div>
                            <p class="text-strong font-medium">{{ $leave->borrowerName() }} @if($leave->isGuest())<span class="badge-neutral ml-1">Guest</span>@endif</p>
                            <p class="text-muted capitalize">{{ $leave->type }} &middot; {{ $leave->start_date->format('d M') }}&ndash;{{ $leave->end_date->format('d M Y') }}</p>
                        </div>
                        <span class="badge-warning">Pending</span>
                    </div>
                @empty
                    <p class="empty-state">No pending leave requests.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="section-head">
                <h2 class="section-title">Pending Borrow Requests</h2>
                <a href="{{ route('admin.borrow.index') }}" class="link link-accent">View all &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentBorrow as $borrow)
                    <div class="list-row">
                        <div>
                            <p class="text-strong font-medium">{{ $borrow->borrowerName() }} @if($borrow->isGuest())<span class="badge-neutral ml-1">Guest</span>@endif</p>
                            <p class="text-muted">{{ $borrow->equipment->code }} &middot; {{ $borrow->borrow_date->format('d M') }}&ndash;{{ $borrow->return_date->format('d M Y') }}</p>
                        </div>
                        <span class="badge-warning">Pending</span>
                    </div>
                @empty
                    <p class="empty-state">No pending borrow requests.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
