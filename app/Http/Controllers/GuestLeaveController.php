<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class GuestLeaveController extends Controller
{
    public function create()
    {
        return view('leave.guest');
    }

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

        LeaveRequest::create([
            ...$validated,
            'user_id' => null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your leave application has been submitted for review. Thank you, '.$validated['guest_name'].'.');
    }
}
