<x-admin-layout>
    <x-slot name="header">Leave Requests</x-slot>

    <div class="card">
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                <a href="{{ route('admin.leave.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $status === $key ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Requester</th>
                        <th class="py-2 pr-4">Type</th>
                        <th class="py-2 pr-4">Dates</th>
                        <th class="py-2 pr-4">Reason</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 pr-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveRequests as $leave)
                        <tr class="border-b border-gray-100 last:border-0 align-top">
                            <td class="py-2 pr-4">
                                <p class="font-medium text-gray-800">{{ $leave->borrowerName() }}</p>
                                @if ($leave->isGuest())
                                    <span class="badge-gray">Guest</span>
                                    <p class="text-xs text-gray-400">{{ $leave->guest_email }}</p>
                                @endif
                            </td>
                            <td class="py-2 pr-4 capitalize">{{ $leave->type }}</td>
                            <td class="py-2 pr-4">{{ $leave->start_date->format('d M Y') }} &ndash; {{ $leave->end_date->format('d M Y') }}<br><span class="text-xs text-gray-400">{{ $leave->totalDays() }} day(s)</span></td>
                            <td class="py-2 pr-4 text-gray-600">{{ $leave->reason ?: '—' }}</td>
                            <td class="py-2 pr-4">
                                @if ($leave->status === 'approved')
                                    <span class="badge-green">Approved</span>
                                @elseif ($leave->status === 'rejected')
                                    <span class="badge-red">Rejected</span>
                                @else
                                    <span class="badge-yellow">Pending</span>
                                @endif
                                @if ($leave->reviewer)
                                    <p class="text-xs text-gray-400 mt-1">by {{ $leave->reviewer->name }}</p>
                                @endif
                            </td>
                            <td class="py-2 pr-4">
                                @if ($leave->status === 'pending')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.leave.approve', $leave) }}">
                                            @csrf
                                            <button class="text-sm text-green-600 hover:underline">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.leave.reject', $leave) }}">
                                            @csrf
                                            <button class="text-sm text-red-600 hover:underline">Reject</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-gray-400 text-center">No leave requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaveRequests->links() }}
        </div>
    </div>
</x-admin-layout>
