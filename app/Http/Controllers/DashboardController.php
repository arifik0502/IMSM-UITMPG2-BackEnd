<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $today = $user->todayAttendance();

        $openBreak = $today ? $today->openBreak() : null;

        $recent = $user->attendances()
            ->orderByDesc('date')
            ->limit(7)
            ->get();

        $stats = [
            'this_month_days' => $user->attendances()
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count(),
            'late_this_month' => $user->attendances()
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('late_minutes', '>', 0)
                ->count(),
            'overtime_minutes_this_month' => (int) $user->attendances()
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('overtime_minutes'),
        ];

        return view('dashboard.index', [
            'today' => $today,
            'openBreak' => $openBreak,
            'recent' => $recent,
            'stats' => $stats,
            'workStart' => config('company.work_start'),
            'workEnd' => config('company.work_end'),
        ]);
    }
}
