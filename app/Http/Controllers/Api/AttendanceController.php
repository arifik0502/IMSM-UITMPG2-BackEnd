<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    /**
     * Today's attendance snapshot — used by the dashboard to know whether
     * to show "Clock in" or "Clock out" / break controls.
     */
    public function status(Request $request)
    {
        $attendance = $request->user()->todayAttendance()?->load('breaks');

        return response()->json([
            'attendance' => $attendance,
            'open_break' => $attendance?->openBreak(),
        ]);
    }

    /**
     * Clock in for today, storing a selfie photo captured from the webcam
     * (sent as a base64 data: URL, matching the original web app's format).
     */
    public function clockIn(Request $request)
    {
        $validated = $request->validate([
            'photo' => ['required', 'string', 'starts_with:data:image/'],
            'work_location' => ['required', 'in:office,home'],
        ]);

        $user = $request->user();
        $today = $user->todayAttendance();

        if ($today && $today->clock_in) {
            throw ValidationException::withMessages([
                'photo' => ['You have already clocked in today.'],
            ]);
        }

        $attendance = $today ?? new Attendance([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
        ]);

        $attendance->work_location = $validated['work_location'];
        $attendance->clock_in = now();
        $attendance->clock_in_photo = $this->storeSelfie($validated['photo'], 'in');
        $attendance->save();

        $attendance->recalculateTimings();
        $attendance->save();

        $locationLabel = $attendance->isWorkFromHome() ? 'from home' : 'at the office';

        $message = $attendance->late_minutes > 0
            ? "Clocked in {$locationLabel}, but you're {$attendance->late_minutes} minute(s) late."
            : "Clocked in {$locationLabel} successfully. Have a great day!";

        return response()->json([
            'message' => $message,
            'attendance' => $attendance,
        ]);
    }

    /**
     * Clock out for today, storing a selfie photo captured from the webcam.
     */
    public function clockOut(Request $request)
    {
        $validated = $request->validate([
            'photo' => ['required', 'string', 'starts_with:data:image/'],
        ]);

        $user = $request->user();
        $attendance = $user->todayAttendance();

        if (! $attendance || ! $attendance->clock_in) {
            throw ValidationException::withMessages([
                'photo' => ['You need to clock in before you can clock out.'],
            ]);
        }

        if ($attendance->clock_out) {
            throw ValidationException::withMessages([
                'photo' => ['You have already clocked out today.'],
            ]);
        }

        if ($attendance->openBreak()) {
            throw ValidationException::withMessages([
                'photo' => ['Please end your current break before clocking out.'],
            ]);
        }

        $attendance->clock_out = now();
        $attendance->clock_out_photo = $this->storeSelfie($validated['photo'], 'out');
        $attendance->recalculateTimings();
        $attendance->save();

        $message = $attendance->overtime_minutes > 0
            ? "Clocked out successfully. You worked {$attendance->overtime_minutes} minute(s) of overtime."
            : 'Clocked out successfully. See you tomorrow!';

        return response()->json([
            'message' => $message,
            'attendance' => $attendance,
        ]);
    }

    /**
     * Attendance history with daily / weekly / monthly filtering.
     */
    public function history(Request $request)
    {
        $period = $request->query('period', 'monthly');
        $user = $request->user();

        $query = $user->attendances()->with('breaks')->orderByDesc('date');

        match ($period) {
            'daily' => $query->whereDate('date', now()->toDateString()),
            'weekly' => $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]),
            default => $query->whereMonth('date', now()->month)->whereYear('date', now()->year),
        };

        $attendances = $query->paginate(15)->withQueryString();

        return response()->json([
            'period' => $period,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Decode a base64 data-URL image and store it on the configured photo disk.
     */
    private function storeSelfie(string $dataUrl, string $suffix): string
    {
        [$meta, $data] = explode(',', $dataUrl, 2) + [null, null];

        $extension = 'jpg';
        if ($meta && preg_match('/data:image\/(\w+);base64/', $meta, $matches)) {
            $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        }

        $binary = base64_decode($data);

        $filename = 'attendance-photos/'.now()->format('Y/m/d').'/'.Str::uuid()."-{$suffix}.{$extension}";

        Storage::disk(env('ATTENDANCE_PHOTOS_DISK', 'public'))->put($filename, $binary);

        return $filename;
    }
}
