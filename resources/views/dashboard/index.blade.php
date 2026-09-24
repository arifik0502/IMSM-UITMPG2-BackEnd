<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Clock in/out card --}}
        <div class="lg:col-span-2 card">
            <div class="section-head">
                <div>
                    <p class="text-sm text-muted">Today</p>
                    <p class="text-strong text-lg font-semibold tracking-tight">{{ now()->format('l, d F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-muted">Work hours</p>
                    <p class="text-sm font-medium text-strong">{{ $workStart }} &ndash; {{ $workEnd }}</p>
                </div>
            </div>

                        @if (! $today || ! $today->clock_in)
                <div class="py-6 text-center sm:py-8">
                    <p class="text-muted mb-4">You haven't clocked in yet today. How are you working today?</p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <button type="button" data-open-camera="clock-in" data-work-location="office" class="btn-primary btn-lg">
                            🏢 Clock In — Office
                        </button>
                        <button type="button" data-open-camera="clock-in" data-work-location="home" class="btn-secondary btn-lg">
                            🏠 Clock In — Work From Home
                        </button>
                    </div>
                </div>
            @elseif (! $today->clock_out)
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="badge-success">Clocked in at {{ $today->clock_in->format('h:i A') }}</span>
                        <span class="badge-brand">{{ $today->isWorkFromHome() ? '🏠 Work From Home' : '🏢 Office' }}</span>
                        @if ($today->late_minutes > 0)
                            <span class="badge-warning">Late by {{ $today->late_minutes }} min</span>
                        @else
                            <span class="badge-neutral">On time</span>
                        @endif
                        @if ($openBreak)
                            <span class="badge-warning">On break since {{ $openBreak->break_start->format('h:i A') }}</span>
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
                        <p class="field-hint">End your break before you can clock out.</p>
                    @endif
                </div>
            @else
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="badge-neutral">In: {{ $today->clock_in->format('h:i A') }}</span>
                        <span class="badge-neutral">Out: {{ $today->clock_out->format('h:i A') }}</span>
                        <span class="badge-brand">{{ $today->isWorkFromHome() ? '🏠 Work From Home' : '🏢 Office' }}</span>
                        @if ($today->late_minutes > 0)
                            <span class="badge-warning">Late {{ $today->late_minutes }} min</span>
                        @endif
                        @if ($today->overtime_minutes > 0)
                            <span class="badge-success">Overtime {{ $today->overtime_minutes }} min</span>
                        @endif
                    </div>
                    <p class="text-muted text-sm">You've completed attendance for today. See you tomorrow!</p>
                </div>
            @endif
        </div>

        {{-- Stats --}}
        <div class="space-y-4">
            <div class="card">
                <p class="stat-label">Days recorded this month</p>
                <p class="stat-value">{{ $stats['this_month_days'] }}</p>
            </div>
            <div class="card">
                <p class="stat-label">Late arrivals this month</p>
                <p class="stat-value">{{ $stats['late_this_month'] }}</p>
            </div>
            <div class="card">
                <p class="stat-label">Overtime minutes this month</p>
                <p class="stat-value">{{ $stats['overtime_minutes_this_month'] }}</p>
            </div>
            <div class="card flex flex-col gap-3">
                <a href="{{ route('borrow.create') }}" class="btn-secondary">Borrow Equipment</a>
                <a href="{{ route('chat.index') }}" class="btn-secondary">Chat</a>
            </div>
        </div>
    </div>

    {{-- Recent attendance --}}
    <div class="card mt-6">
        <h2 class="section-title mb-4">Recent Attendance</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                        <tr>
                        <th>Date</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $record)
                        <tr>
                            <td>{{ $record->date->format('d M Y') }}</td>
                            <td>{{ $record->clock_in?->format('h:i A') ?? '—' }}</td>
                            <td>{{ $record->clock_out?->format('h:i A') ?? '—' }}</td>
                            <td>
                                <span class="badge-brand">{{ $record->isWorkFromHome() ? '🏠 Home' : '🏢 Office' }}</span>
                            </td>
                            <td>
                                @if ($record->late_minutes > 0)
                                    <span class="badge-warning">Late</span>
                                @else
                                    <span class="badge-success">On time</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No attendance records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('attendance.history') }}" class="link link-accent">View full history &rarr;</a>
        </div>
    </div>

    @include('attendance.camera-modal')
</x-app-layout>
