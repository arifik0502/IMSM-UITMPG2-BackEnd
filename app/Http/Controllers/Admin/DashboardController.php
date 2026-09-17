<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BorrowRequest;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_employees' => User::where('role', 'employee')->count(),
            'clocked_in_today' => Attendance::whereDate('date', now()->toDateString())
                ->whereNotNull('clock_in')
                ->count(),
            'pending_leave' => LeaveRequest::where('status', 'pending')->count(),
            'pending_borrow' => BorrowRequest::where('status', 'pending')->count(),
            'overdue_borrow' => BorrowRequest::where('status', 'approved')
                ->whereNull('actual_returned_at')
                ->where('return_date', '<', now()->toDateString())
                ->count(),
        ];

        $recentLeave = LeaveRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $recentBorrow = BorrowRequest::with(['equipment', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentLeave' => $recentLeave,
            'recentBorrow' => $recentBorrow,
        ]);
    }
}
