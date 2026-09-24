<x-admin-layout>
    <x-slot name="header">Borrow Requests</x-slot>

    <div class="card">
        <div class="seg mb-6">
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                <a href="{{ route('admin.borrow.index', ['status' => $key]) }}"
                   class="seg-item" @if ($status === $key) aria-current="page" @endif>
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="table-wrap">
            <table class="data-table data-table-top">
                <thead>
                    <tr>
                        <th>Borrower</th>
                        <th>Equipment</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowRequests as $borrow)
                        <tr>
                            <td>
                                <p class="cell-strong">{{ $borrow->borrowerName() }}</p>
                                @if ($borrow->isGuest())
                                    <span class="badge-neutral mt-1">Guest</span>
                                    <p class="text-xs text-muted">{{ $borrow->guest_email }}</p>
                                @endif
                            </td>
                            <td>{{ $borrow->equipment->code }}<br><span class="text-xs text-muted">{{ $borrow->equipment->category }}</span></td>
                            <td>{{ $borrow->borrow_date->format('d M Y') }} &ndash; {{ $borrow->return_date->format('d M Y') }}</td>
                            <td class="text-muted">{{ $borrow->reason ?: '—' }}</td>
                            <td>
                                @if ($borrow->status === 'approved')
                                    @if ($borrow->actual_returned_at)
                                        <span class="badge-neutral">Returned</span>
                                    @elseif ($borrow->returnStatus() === 'overdue')
                                        <span class="badge-error">Overdue</span>
                                    @else
                                        <span class="badge-success">Active</span>
                                    @endif
                                @elseif ($borrow->status === 'rejected')
                                    <span class="badge-error">Rejected</span>
                                @else
                                    <span class="badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if ($borrow->status === 'pending')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.borrow.approve', $borrow) }}">
                                            @csrf
                                            <button class="link link-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.borrow.reject', $borrow) }}">
                                            @csrf
                                            <button class="link link-danger">Reject</button>
                                        </form>
                                    </div>
                                @elseif ($borrow->status === 'approved' && ! $borrow->actual_returned_at)
                                    <form method="POST" action="{{ route('admin.borrow.return', $borrow) }}">
                                        @csrf
                                        <button class="link link-accent">Mark Returned</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No borrow requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $borrowRequests->links() }}
        </div>
    </div>
</x-admin-layout>
