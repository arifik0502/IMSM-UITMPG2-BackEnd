<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $employees = User::query()
            ->withCount(['attendances', 'leaveRequests', 'borrowRequests'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return response()->json($employees);
    }

    public function show(Request $request, User $employee)
    {
        $period = $request->query('period', 'monthly');
        $location = $request->query('location', 'all');

        $query = $employee->attendances()->with('breaks')->orderByDesc('date');

        match ($period) {
            'daily' => $query->whereDate('date', now()->toDateString()),
            'weekly' => $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]),
            default => $query->whereMonth('date', now()->month)->whereYear('date', now()->year),
        };

        if (in_array($location, ['office', 'home'], true)) {
            $query->where('work_location', $location);
        }

        $attendances = $query->paginate(15)->withQueryString();

        $stats = [
            'total_records' => $employee->attendances()->count(),
            'late_count' => $employee->attendances()->where('late_minutes', '>', 0)->count(),
            'overtime_minutes' => (int) $employee->attendances()->sum('overtime_minutes'),
            'wfh_count' => $employee->attendances()->where('work_location', 'home')->count(),
        ];

        return response()->json([
            'employee' => $employee,
            'attendances' => $attendances,
            'period' => $period,
            'location' => $location,
            'stats' => $stats,
        ]);
    }
}
