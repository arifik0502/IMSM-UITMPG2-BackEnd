<x-admin-layout>
    <x-slot name="header">Leave Requests</x-slot>

    <div class="card">
        <div class="seg mb-6">
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                <a href="{{ route('admin.leave.index', ['status' => $key]) }}"
                   class="seg-item" @if ($status === $key) aria-current="page" @endif>
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="table-wrap">
            <table class="data-table data-table-top">
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveRequests as $leave)
                        <tr>
                            <td>
                                <p class="cell-strong">{{ $leave->borrowerName() }}</p>
                                @if ($leave->isGuest())
                                    <span class="badge-neutral mt-1">Guest</span>
                                    <p class="text-xs text-muted">{{ $leave->guest_email }}</p>
                                @endif
                            </td>
                            <td class="capitalize">{{ $leave->type }}</td>
                            <td>{{ $leave->start_date->format('d M Y') }} &ndash; {{ $leave->end_date->format('d M Y') }}<br><span class="text-xs text-muted">{{ $leave->totalDays() }} day(s)</span></td>
                            <td class="text-muted">{{ $leave->reason ?: '—' }}</td>
                            <td>
                                @if ($leave->status === 'approved')
                                    <span class="badge-success">Approved</span>
                                @elseif ($leave->status === 'rejected')
                                    <span class="badge-error">Rejected</span>
                                @else
                                    <span class="badge-warning">Pending</span>
                                @endif
                                @if ($leave->reviewer)
                                    <p class="text-xs text-muted mt-1">by {{ $leave->reviewer->name }}</p>
                                @endif
                            </td>
                            <td>
                                @if ($leave->status === 'pending')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.leave.approve', $leave) }}">
                                            @csrf
                                            <button class="link link-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.leave.reject', $leave) }}">
                                            @csrf
                                            <button class="link link-danger">Reject</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No leave requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaveRequests->links() }}
        </div>
    </div>
</x-admin-layout>
