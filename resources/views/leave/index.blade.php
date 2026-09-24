<x-app-layout>
    <x-slot name="header">Leave / Time-off</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-1 h-fit">
            <h2 class="section-title mb-4">Request Leave</h2>

            <form method="POST" action="{{ route('leave.store') }}" class="space-y-4">
                @csrf

                <div class="field">
                    <x-input-label for="type" value="Type" />
                    <select id="type" name="type" required
                            class="form-field">
                        <option value="annual" @selected(old('type') === 'annual')>Annual Leave</option>
                        <option value="sick" @selected(old('type') === 'sick')>Sick Leave</option>
                        <option value="unpaid" @selected(old('type') === 'unpaid')>Unpaid Leave</option>
                        <option value="other" @selected(old('type') === 'other')>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" />
                </div>

                <div class="field">
                    <x-input-label for="start_date" value="Start Date" />
                    <x-text-input id="start_date" type="date" name="start_date" :value="old('start_date')" required />
                    <x-input-error :messages="$errors->get('start_date')" />
                </div>

                <div class="field">
                    <x-input-label for="end_date" value="End Date" />
                    <x-text-input id="end_date" type="date" name="end_date" :value="old('end_date')" required />
                    <x-input-error :messages="$errors->get('end_date')" />
                </div>

                <div class="field">
                    <x-input-label for="reason" value="Reason (optional)" />
                    <textarea id="reason" name="reason" rows="3"
                              class="form-field">{{ old('reason') }}</textarea>
                    <x-input-error :messages="$errors->get('reason')" />
                </div>

                <x-primary-button class="w-full">Submit Request</x-primary-button>
                <p class="field-hint">Your request will be reviewed by an admin before it's approved.</p>
            </form>
        </div>

        <div class="card lg:col-span-2">
            <h2 class="section-title mb-4">Your Leave History</h2>

            <div class="table-wrap">
                <table class="data-table data-table-top">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaveRequests as $leave)
                            <tr>
                                <td class="capitalize">{{ $leave->type }}</td>
                                <td>{{ $leave->start_date->format('d M Y') }}</td>
                                <td>{{ $leave->end_date->format('d M Y') }}</td>
                                <td>{{ $leave->totalDays() }}</td>
                                <td class="text-muted">{{ $leave->reason ?: '—' }}</td>
                                <td>
                                    @if ($leave->status === 'approved')
                                        <span class="badge-success">Approved</span>
                                    @elseif ($leave->status === 'rejected')
                                        <span class="badge-error">Rejected</span>
                                    @else
                                        <span class="badge-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-state">No leave requests yet.</td></tr>
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
