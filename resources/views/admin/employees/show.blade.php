<x-admin-layout>
    <x-slot name="header">{{ $employee->name }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="text-sm text-brand-600 hover:underline">&larr; Back to employees</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="card">
            <p class="text-sm text-gray-500">Total Attendance Records</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_records'] }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-500">Late Arrivals</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['late_count'] }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-500">Total Overtime Minutes</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['overtime_minutes'] }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-500">🏠 Work From Home Days</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['wfh_count'] }}</p>
        </div>
    </div>

    <div class="card mb-6">
        <p class="text-sm text-gray-500">Email</p>
        <p class="text-gray-800">{{ $employee->email }}</p>
    </div>

    <div class="card">
        <h2 class="font-semibold text-gray-800 mb-4">Attendance History</h2>

        <div class="flex flex-wrap gap-2 mb-3">
            @foreach (['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month'] as $key => $label)
                <a href="{{ route('admin.employees.show', [$employee, 'period' => $key, 'location' => $location]) }}"
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $period === $key ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-2 mb-6">
            @foreach (['all' => 'All Locations', 'office' => '🏢 Office', 'home' => '🏠 Work From Home'] as $key => $label)
                <a href="{{ route('admin.employees.show', [$employee, 'period' => $period, 'location' => $key]) }}"
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $location === $key ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Date</th>
                        <th class="py-2 pr-4">Clock In</th>
                        <th class="py-2 pr-4">Clock Out</th>
                        <th class="py-2 pr-4">Location</th>
                        <th class="py-2 pr-4">Break (min)</th>
                        <th class="py-2 pr-4">Late (min)</th>
                        <th class="py-2 pr-4">Overtime (min)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $record)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="py-2 pr-4">{{ $record->date->format('d M Y') }}</td>
                            <td class="py-2 pr-4">{{ $record->clock_in?->format('h:i A') ?? '—' }}</td>
                            <td class="py-2 pr-4">{{ $record->clock_out?->format('h:i A') ?? '—' }}</td>
                            <td class="py-2 pr-4">
                                <span class="badge-blue">{{ $record->isWorkFromHome() ? '🏠 Home' : '🏢 Office' }}</span>
                            </td>
                            <td class="py-2 pr-4">{{ $record->totalBreakMinutes() }}</td>
                            <td class="py-2 pr-4">{{ $record->late_minutes }}</td>
                            <td class="py-2 pr-4">{{ $record->overtime_minutes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-gray-400 text-center">No attendance records for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</x-admin-layout>
