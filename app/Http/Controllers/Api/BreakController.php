<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BreakTime;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BreakController extends Controller
{
    public function start(Request $request)
    {
        $user = $request->user();
        $attendance = $user->todayAttendance();

        if (! $attendance || ! $attendance->clock_in) {
            throw ValidationException::withMessages(['break' => ['Clock in before starting a break.']]);
        }

        if ($attendance->clock_out) {
            throw ValidationException::withMessages(['break' => ['You have already clocked out for today.']]);
        }

        if ($attendance->openBreak()) {
            throw ValidationException::withMessages(['break' => ['You already have a break in progress.']]);
        }

        $break = BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        return response()->json([
            'message' => 'Break started. Enjoy!',
            'break' => $break,
        ]);
    }

    public function end(Request $request)
    {
        $user = $request->user();
        $attendance = $user->todayAttendance();

        $break = $attendance ? $attendance->openBreak() : null;

        if (! $break) {
            throw ValidationException::withMessages(['break' => ['No break in progress.']]);
        }

        $break->update(['break_end' => now()]);

        $minutes = $break->durationInMinutes();

        return response()->json([
            'message' => "Break ended. You were away for {$minutes} minute(s).",
            'break' => $break,
        ]);
    }
}
