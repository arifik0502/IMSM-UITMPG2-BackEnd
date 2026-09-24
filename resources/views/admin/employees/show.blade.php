<x-admin-layout>
    <x-slot name="header">{{ $employee->name }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="link link-accent">&larr; Back to employees</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card">
            <p class="stat-label">Total Attendance Records</p>
            <p class="stat-value">{{ $stats['total_records'] }}</p>
        </div>
        <div class="card">
            <p class="stat-label">Late Arrivals</p>
            <p class="stat-value">{{ $stats['late_count'] }}</p>
        </div>
        <div class="card">
            <p class="stat-label">Total Overtime Minutes</p>
            <p class="stat-value">{{ $stats['overtime_minutes'] }}</p>
        </div>
        <div class="card">
            <p class="stat-label">🏠 Work From Home Days</p>
            <p class="stat-value">{{ $stats['wfh_count'] }}</p>
        </div>
    </div>

    <div class="card mb-6">
        <p class="text-sm text-muted">Email</p>
        <p class="text-strong">{{ $employee->email }}</p>
    </div>

    <div class="card">
        <h2 class="section-title mb-4">Attendance History</h2>

        <div class="filter-bar">
            <div class="seg">
                @foreach (['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month'] as $key => $label)
                    <a href="{{ route('admin.employees.show', [$employee, 'period' => $key, 'location' => $location]) }}"
                       class="seg-item" @if ($period === $key) aria-current="page" @endif>
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="seg">
                @foreach (['all' => 'All Locations', 'office' => '🏢 Office', 'home' => '🏠 Work From Home'] as $key => $label)
                    <a href="{{ route('admin.employees.show', [$employee, 'period' => $period, 'location' => $key]) }}"
                       class="seg-item" @if ($location === $key) aria-current="page" @endif>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
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
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">No attendance records for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</x-admin-layout>
