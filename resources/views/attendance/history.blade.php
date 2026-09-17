<x-app-layout>
    <x-slot name="header">Attendance History</x-slot>

    <div class="card">
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach (['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month'] as $key => $label)
                <a href="{{ route('attendance.history', ['period' => $key]) }}"
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $period === $key ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
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
                        <th class="py-2 pr-4">Status</th>
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
                            <td class="py-2 pr-4">
                                @if ($record->late_minutes > 0)
                                    <span class="badge-yellow">Late</span>
                                @else
                                    <span class="badge-green">On time</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-6 text-gray-400 text-center">No attendance records for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</x-app-layout>
