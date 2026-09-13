<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Clock in for today, storing a selfie photo captured from the webcam.
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
            return back()->with('error', 'You have already clocked in today.');
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

        return back()->with('success', $message);
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
            return back()->with('error', 'You need to clock in before you can clock out.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        if ($attendance->openBreak()) {
            return back()->with('error', 'Please end your current break before clocking out.');
        }

        $attendance->clock_out = now();
        $attendance->clock_out_photo = $this->storeSelfie($validated['photo'], 'out');
        $attendance->recalculateTimings();
        $attendance->save();

        $message = $attendance->overtime_minutes > 0
            ? "Clocked out successfully. You worked {$attendance->overtime_minutes} minute(s) of overtime."
            : 'Clocked out successfully. See you tomorrow!';

        return back()->with('success', $message);
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

        return view('attendance.history', [
            'attendances' => $attendances,
            'period' => $period,
        ]);
    }

    /**
     * Decode a base64 data-URL image and store it on the public disk.
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

        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
