<x-admin-layout>
    <x-slot name="header">Borrow Requests</x-slot>

    <div class="card">
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                <a href="{{ route('admin.borrow.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $status === $key ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Borrower</th>
                        <th class="py-2 pr-4">Equipment</th>
                        <th class="py-2 pr-4">Dates</th>
                        <th class="py-2 pr-4">Reason</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 pr-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowRequests as $borrow)
                        <tr class="border-b border-gray-100 last:border-0 align-top">
                            <td class="py-2 pr-4">
                                <p class="font-medium text-gray-800">{{ $borrow->borrowerName() }}</p>
                                @if ($borrow->isGuest())
                                    <span class="badge-gray">Guest</span>
                                    <p class="text-xs text-gray-400">{{ $borrow->guest_email }}</p>
                                @endif
                            </td>
                            <td class="py-2 pr-4">{{ $borrow->equipment->code }}<br><span class="text-xs text-gray-400">{{ $borrow->equipment->category }}</span></td>
                            <td class="py-2 pr-4">{{ $borrow->borrow_date->format('d M Y') }} &ndash; {{ $borrow->return_date->format('d M Y') }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ $borrow->reason ?: '—' }}</td>
                            <td class="py-2 pr-4">
                                @if ($borrow->status === 'approved')
                                    @if ($borrow->actual_returned_at)
                                        <span class="badge-gray">Returned</span>
                                    @elseif ($borrow->returnStatus() === 'overdue')
                                        <span class="badge-red">Overdue</span>
                                    @else
                                        <span class="badge-green">Active</span>
                                    @endif
                                @elseif ($borrow->status === 'rejected')
                                    <span class="badge-red">Rejected</span>
                                @else
                                    <span class="badge-yellow">Pending</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4">
                                @if ($borrow->status === 'pending')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.borrow.approve', $borrow) }}">
                                            @csrf
                                            <button class="text-sm text-green-600 hover:underline">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.borrow.reject', $borrow) }}">
                                            @csrf
                                            <button class="text-sm text-red-600 hover:underline">Reject</button>
                                        </form>
                                    </div>
                                @elseif ($borrow->status === 'approved' && ! $borrow->actual_returned_at)
                                    <form method="POST" action="{{ route('admin.borrow.return', $borrow) }}">
                                        @csrf
                                        <button class="text-sm text-brand-600 hover:underline">Mark Returned</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-gray-400 text-center">No borrow requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $borrowRequests->links() }}
        </div>
    </div>
</x-admin-layout>
