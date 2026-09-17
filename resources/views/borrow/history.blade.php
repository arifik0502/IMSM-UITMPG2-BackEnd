<x-app-layout>
    <x-slot name="header">My Borrow History</x-slot>

    <div class="mb-6">
        <a href="{{ route('borrow.create') }}" class="text-sm text-brand-600 hover:underline">&larr; Book more equipment</a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Equipment</th>
                        <th class="py-2 pr-4">Borrow Date</th>
                        <th class="py-2 pr-4">Return Date</th>
                        <th class="py-2 pr-4">Reason</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 pr-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowRequests as $borrow)
                        <tr class="border-b border-gray-100 last:border-0 align-top">
                            <td class="py-2 pr-4 font-medium">{{ $borrow->equipment->code }}</td>
                            <td class="py-2 pr-4">{{ $borrow->borrow_date->format('d M Y') }}</td>
                            <td class="py-2 pr-4">{{ $borrow->return_date->format('d M Y') }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ $borrow->reason ?: '—' }}</td>
                            <td class="py-2 pr-4">
                                @if ($borrow->status === 'pending')
                                    <span class="badge-yellow">Pending Approval</span>
                                @elseif ($borrow->status === 'rejected')
                                    <span class="badge-red">Rejected</span>
                                @elseif ($borrow->actual_returned_at)
                                    <span class="badge-gray">Returned</span>
                                @elseif ($borrow->returnStatus() === 'overdue')
                                    <span class="badge-red">Overdue</span>
                                @else
                                    <span class="badge-green">Active</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4">
                                @if ($borrow->status === 'approved' && ! $borrow->actual_returned_at)
                                    <form method="POST" action="{{ route('borrow.return', $borrow) }}">
                                        @csrf
                                        <button class="text-sm text-brand-600 hover:underline">Mark Returned</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-gray-400 text-center">No equipment bookings yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $borrowRequests->links() }}
        </div>
    </div>
</x-app-layout>
