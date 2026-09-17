<x-admin-layout>
    <x-slot name="header">Employees</x-slot>

    <div class="card">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="mb-6 flex gap-2">
            <x-text-input type="text" name="search" placeholder="Search by name or email..." :value="$search" class="max-w-sm" />
            <button class="btn-secondary">Search</button>
            @if ($search)
                <a href="{{ route('admin.employees.index') }}" class="btn-secondary">Clear</a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Name</th>
                        <th class="py-2 pr-4">Email</th>
                        <th class="py-2 pr-4">Attendance Records</th>
                        <th class="py-2 pr-4">Leave Requests</th>
                        <th class="py-2 pr-4">Borrow Requests</th>
                        <th class="py-2 pr-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="py-2 pr-4 font-medium">{{ $employee->name }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ $employee->email }}</td>
                            <td class="py-2 pr-4">{{ $employee->attendances_count }}</td>
                            <td class="py-2 pr-4">{{ $employee->leave_requests_count }}</td>
                            <td class="py-2 pr-4">{{ $employee->borrow_requests_count }}</td>
                            <td class="py-2 pr-4">
                                <a href="{{ route('admin.employees.show', $employee) }}" class="text-brand-600 hover:underline">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-gray-400 text-center">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</x-admin-layout>
