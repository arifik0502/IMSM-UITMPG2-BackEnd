<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class GuestLeaveController extends Controller
{
    /**
     * Public endpoint — no account needed. Mirrors the original app's
     * /guest/leave form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'type' => ['required', 'in:annual,sick,unpaid,other'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $leaveRequest = LeaveRequest::create([
            ...$validated,
            'user_id' => null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Your leave application has been submitted for review. Thank you, '.$validated['guest_name'].'.',
            'leave_request' => $leaveRequest,
        ], 201);
    }
}
