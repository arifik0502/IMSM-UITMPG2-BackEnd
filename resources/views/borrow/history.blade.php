<x-app-layout>
    <x-slot name="header">My Borrow History</x-slot>

    <div class="mb-6">
        <a href="{{ route('borrow.create') }}" class="link link-accent">&larr; Book more equipment</a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="data-table data-table-top">
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowRequests as $borrow)
                        <tr>
                            <td class="cell-strong">{{ $borrow->equipment->code }}</td>
                            <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                            <td>{{ $borrow->return_date->format('d M Y') }}</td>
                            <td class="text-muted">{{ $borrow->reason ?: '—' }}</td>
                            <td>
                                @if ($borrow->status === 'pending')
                                    <span class="badge-warning">Pending Approval</span>
                                @elseif ($borrow->status === 'rejected')
                                    <span class="badge-error">Rejected</span>
                                @elseif ($borrow->actual_returned_at)
                                    <span class="badge-neutral">Returned</span>
                                @elseif ($borrow->returnStatus() === 'overdue')
                                    <span class="badge-error">Overdue</span>
                                @else
                                    <span class="badge-success">Active</span>
                                @endif
                            </td>
                            <td>
                                @if ($borrow->status === 'approved' && ! $borrow->actual_returned_at)
                                    <form method="POST" action="{{ route('borrow.return', $borrow) }}">
                                        @csrf
                                        <button class="link link-accent">Mark Returned</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No equipment bookings yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $borrowRequests->links() }}
        </div>
    </div>
</x-app-layout>
