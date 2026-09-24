<x-admin-layout>
    <x-slot name="header">Employees</x-slot>

    <div class="card">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="mb-6 flex flex-wrap gap-2">
            <x-text-input type="text" name="search" placeholder="Search by name or email..." :value="$search" class="min-w-0 flex-1 sm:max-w-sm" />
            <button class="btn-secondary">Search</button>
            @if ($search)
                <a href="{{ route('admin.employees.index') }}" class="btn-secondary">Clear</a>
            @endif
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Attendance Records</th>
                        <th>Leave Requests</th>
                        <th>Borrow Requests</th>
                        <th><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="cell-strong">{{ $employee->name }}</td>
                            <td class="text-muted">{{ $employee->email }}</td>
                            <td>{{ $employee->attendances_count }}</td>
                            <td>{{ $employee->leave_requests_count }}</td>
                            <td>{{ $employee->borrow_requests_count }}</td>
                            <td>
                                <a href="{{ route('admin.employees.show', $employee) }}" class="link link-accent">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</x-admin-layout>
