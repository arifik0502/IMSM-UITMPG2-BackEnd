<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Clock in/out card --}}
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500">Today</p>
                    <p class="text-lg font-semibold text-gray-800">{{ now()->format('l, d F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Work hours</p>
                    <p class="text-sm font-medium text-gray-700">{{ $workStart }} &ndash; {{ $workEnd }}</p>
                </div>
            </div>

                        @if (! $today || ! $today->clock_in)
                <div class="text-center py-8">
                    <p class="text-gray-500 mb-4">You haven't clocked in yet today. How are you working today?</p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <button type="button" data-open-camera="clock-in" data-work-location="office" class="btn-primary text-base px-6 py-3">
                            🏢 Clock In — Office
                        </button>
                        <button type="button" data-open-camera="clock-in" data-work-location="home" class="btn-secondary text-base px-6 py-3">
                            🏠 Clock In — Work From Home
                        </button>
                    </div>
                </div>
            @elseif (! $today->clock_out)
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="badge-green">Clocked in at {{ $today->clock_in->format('h:i A') }}</span>
                        <span class="badge-blue">{{ $today->isWorkFromHome() ? '🏠 Work From Home' : '🏢 Office' }}</span>
                        @if ($today->late_minutes > 0)
                            <span class="badge-yellow">Late by {{ $today->late_minutes }} min</span>
                        @else
                            <span class="badge-gray">On time</span>
                        @endif
                        @if ($openBreak)
                            <span class="badge-yellow">On break since {{ $openBreak->break_start->format('h:i A') }}</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        @if ($openBreak)
                            <form method="POST" action="{{ route('breaks.end') }}">
                                @csrf
                                <button class="btn-secondary">End Break</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('breaks.start') }}">
                                @csrf
                                <button class="btn-secondary">Start Break</button>
                            </form>
                        @endif

                        <button type="button" data-open-camera="clock-out" class="btn-danger" @if($openBreak) disabled @endif>
                            Clock Out
                        </button>
                    </div>
                    @if ($openBreak)
                        <p class="text-xs text-gray-500">End your break before you can clock out.</p>
                    @endif
                </div>
            @else
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="badge-gray">In: {{ $today->clock_in->format('h:i A') }}</span>
                        <span class="badge-gray">Out: {{ $today->clock_out->format('h:i A') }}</span>
                        <span class="badge-blue">{{ $today->isWorkFromHome() ? '🏠 Work From Home' : '🏢 Office' }}</span>
                        @if ($today->late_minutes > 0)
                            <span class="badge-yellow">Late {{ $today->late_minutes }} min</span>
                        @endif
                        @if ($today->overtime_minutes > 0)
                            <span class="badge-green">Overtime {{ $today->overtime_minutes }} min</span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-sm">You've completed attendance for today. See you tomorrow!</p>
                </div>
            @endif
        </div>

        {{-- Stats --}}
        <div class="space-y-4">
            <div class="card">
                <p class="text-sm text-gray-500">Days recorded this month</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['this_month_days'] }}</p>
            </div>
            <div class="card">
                <p class="text-sm text-gray-500">Late arrivals this month</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['late_this_month'] }}</p>
            </div>
            <div class="card">
                <p class="text-sm text-gray-500">Overtime minutes this month</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['overtime_minutes_this_month'] }}</p>
            </div>
            <div class="card flex flex-col gap-2">
                <a href="{{ route('borrow.create') }}" class="btn-secondary justify-center">Borrow Equipment</a>
                <a href="{{ route('chat.index') }}" class="btn-secondary justify-center">Chat</a>
            </div>
        </div>
    </div>

    {{-- Recent attendance --}}
    <div class="card mt-6">
        <h2 class="font-semibold text-gray-800 mb-4">Recent Attendance</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Date</th>
                        <th class="py-2 pr-4">Clock In</th>
                        <th class="py-2 pr-4">Clock Out</th>
                        <th class="py-2 pr-4">Location</th>
                        <th class="py-2 pr-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $record)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="py-2 pr-4">{{ $record->date->format('d M Y') }}</td>
                            <td class="py-2 pr-4">{{ $record->clock_in?->format('h:i A') ?? '—' }}</td>
                            <td class="py-2 pr-4">{{ $record->clock_out?->format('h:i A') ?? '—' }}</td>
                            <td class="py-2 pr-4">
                                <span class="badge-blue">{{ $record->isWorkFromHome() ? '🏠 Home' : '🏢 Office' }}</span>
                            </td>
                            <td class="py-2 pr-4">
                                @if ($record->late_minutes > 0)
                                    <span class="badge-yellow">Late</span>
                                @else
                                    <span class="badge-green">On time</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-gray-400 text-center">No attendance records yet.</td></tr>
                    @endforelse
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('attendance.history') }}" class="text-sm text-brand-600 hover:underline">View full history &rarr;</a>
        </div>
    </div>

    @include('attendance.camera-modal')
</x-app-layout>
