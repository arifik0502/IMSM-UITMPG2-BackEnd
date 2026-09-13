<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $leaveRequests = $request->user()
            ->leaveRequests()
            ->orderByDesc('start_date')
            ->paginate(10);

        return view('leave.index', [
            'leaveRequests' => $leaveRequests,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:annual,sick,unpaid,other'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->leaveRequests()->create([
            ...$validated,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Leave request submitted and is pending admin review.');
    }
}
