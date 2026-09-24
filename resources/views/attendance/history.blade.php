<x-app-layout>
    <x-slot name="header">Attendance History</x-slot>

    <div class="card">
        <div class="seg mb-6">
            @foreach (['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month'] as $key => $label)
                <a href="{{ route('attendance.history', ['period' => $key]) }}"
                   class="seg-item" @if ($period === $key) aria-current="page" @endif>
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Location</th>
                        <th>Break (min)</th>
                        <th>Late (min)</th>
                        <th>Overtime (min)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $record)
                        <tr>
                            <td>{{ $record->date->format('d M Y') }}</td>
                            <td>{{ $record->clock_in?->format('h:i A') ?? '—' }}</td>
                            <td>{{ $record->clock_out?->format('h:i A') ?? '—' }}</td>
                            <td>
                                <span class="badge-brand">{{ $record->isWorkFromHome() ? '🏠 Home' : '🏢 Office' }}</span>
                            </td>
                            <td>{{ $record->totalBreakMinutes() }}</td>
                            <td>{{ $record->late_minutes }}</td>
                            <td>{{ $record->overtime_minutes }}</td>
                            <td>
                                @if ($record->late_minutes > 0)
                                    <span class="badge-warning">Late</span>
                                @else
                                    <span class="badge-success">On time</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-state">No attendance records for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</x-app-layout>
