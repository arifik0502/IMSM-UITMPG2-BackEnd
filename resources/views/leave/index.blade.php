<x-app-layout>
    <x-slot name="header">Leave / Time-off</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-1 h-fit">
            <h2 class="font-semibold text-gray-800 mb-4">Request Leave</h2>

            <form method="POST" action="{{ route('leave.store') }}" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="type" value="Type" />
                    <select id="type" name="type" required
                            class="block w-full form-field">
                        <option value="annual" @selected(old('type') === 'annual')>Annual Leave</option>
                        <option value="sick" @selected(old('type') === 'sick')>Sick Leave</option>
                        <option value="unpaid" @selected(old('type') === 'unpaid')>Unpaid Leave</option>
                        <option value="other" @selected(old('type') === 'other')>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="start_date" value="Start Date" />
                    <x-text-input id="start_date" type="date" name="start_date" :value="old('start_date')" required />
                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="end_date" value="End Date" />
                    <x-text-input id="end_date" type="date" name="end_date" :value="old('end_date')" required />
                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="reason" value="Reason (optional)" />
                    <textarea id="reason" name="reason" rows="3"
                              class="block w-full form-field">{{ old('reason') }}</textarea>
                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                </div>

                <x-primary-button class="w-full">Submit Request</x-primary-button>
                <p class="text-xs text-gray-500">Your request will be reviewed by an admin before it's approved.</p>
            </form>
        </div>

        <div class="card lg:col-span-2">
            <h2 class="font-semibold text-gray-800 mb-4">Your Leave History</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="py-2 pr-4">Type</th>
                            <th class="py-2 pr-4">Start</th>
                            <th class="py-2 pr-4">End</th>
                            <th class="py-2 pr-4">Days</th>
                            <th class="py-2 pr-4">Reason</th>
                            <th class="py-2 pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaveRequests as $leave)
                            <tr class="border-b border-gray-100 last:border-0 align-top">
                                <td class="py-2 pr-4 capitalize">{{ $leave->type }}</td>
                                <td class="py-2 pr-4">{{ $leave->start_date->format('d M Y') }}</td>
                                <td class="py-2 pr-4">{{ $leave->end_date->format('d M Y') }}</td>
                                <td class="py-2 pr-4">{{ $leave->totalDays() }}</td>
                                <td class="py-2 pr-4 text-gray-600">{{ $leave->reason ?: '—' }}</td>
                                <td class="py-2 pr-4">
                                    @if ($leave->status === 'approved')
                                        <span class="badge-green">Approved</span>
                                    @elseif ($leave->status === 'rejected')
                                        <span class="badge-red">Rejected</span>
                                    @else
                                        <span class="badge-yellow">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-gray-400 text-center">No leave requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
