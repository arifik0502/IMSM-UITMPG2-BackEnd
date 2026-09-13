<?php

namespace App\Http\Controllers;

use App\Models\BreakTime;
use Illuminate\Http\Request;

class BreakController extends Controller
{
    public function start(Request $request)
    {
        $user = $request->user();
        $attendance = $user->todayAttendance();

        if (! $attendance || ! $attendance->clock_in) {
            return back()->with('error', 'Clock in before starting a break.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out for today.');
        }

        if ($attendance->openBreak()) {
            return back()->with('error', 'You already have a break in progress.');
        }

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        return back()->with('success', 'Break started. Enjoy!');
    }

    public function end(Request $request)
    {
        $user = $request->user();
        $attendance = $user->todayAttendance();

        $break = $attendance ? $attendance->openBreak() : null;

        if (! $break) {
            return back()->with('error', 'No break in progress.');
        }

        $break->update(['break_end' => now()]);

        $minutes = $break->durationInMinutes();

        return back()->with('success', "Break ended. You were away for {$minutes} minute(s).");
    }
}
