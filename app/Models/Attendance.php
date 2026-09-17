<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attendance extends Model
{
    use HasFactory;

    /**
     * Included automatically in JSON responses so the frontend never has to
     * reconstruct storage URLs itself.
     */
    protected $appends = ['clock_in_photo_url', 'clock_out_photo_url'];

    protected $fillable = [
        'user_id',
        'date',
        'work_location',
        'clock_in',
        'clock_out',
        'clock_in_photo',
        'clock_out_photo',
        'status',
        'late_minutes',
        'overtime_minutes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getClockInPhotoUrlAttribute(): ?string
    {
        return $this->clock_in_photo
            ? Storage::disk(env('ATTENDANCE_PHOTOS_DISK', 'public'))->url($this->clock_in_photo)
            : null;
    }

    public function getClockOutPhotoUrlAttribute(): ?string
    {
        return $this->clock_out_photo
            ? Storage::disk(env('ATTENDANCE_PHOTOS_DISK', 'public'))->url($this->clock_out_photo)
            : null;
    }

     public function isWorkFromHome(): bool
    {
        return $this->work_location === 'home';
    }

    public function breaks()
    {
        return $this->hasMany(BreakTime::class);
    }

    /**
     * Currently open (not yet ended) break for this attendance record, if any.
     */
    public function openBreak()
    {
        return $this->breaks()->whereNull('break_end')->latest('break_start')->first();
    }

    /**
     * Total break duration in minutes (completed breaks only).
     */
    public function totalBreakMinutes(): int
    {
        return $this->breaks
            ->filter(fn ($break) => $break->break_end !== null)
            ->sum(fn ($break) => $break->break_start->diffInMinutes($break->break_end));
    }

    /**
     * Work start/end Carbon instances for this attendance's date, based on company config.
     */
    public function scheduledStart(): Carbon
    {
        return Carbon::parse($this->date->toDateString().' '.config('company.work_start'));
    }

    public function scheduledEnd(): Carbon
    {
        return Carbon::parse($this->date->toDateString().' '.config('company.work_end'));
    }

    /**
     * Recalculate late_minutes / overtime_minutes / status based on clock_in and clock_out.
     */
    public function recalculateTimings(): void
    {
        $grace = (int) config('company.late_grace_minutes', 0);

        if ($this->clock_in) {
            $latestOnTime = $this->scheduledStart()->copy()->addMinutes($grace);
            $this->late_minutes = $this->clock_in->greaterThan($latestOnTime)
                ? $latestOnTime->diffInMinutes($this->clock_in)
                : 0;
        }

        if ($this->clock_out) {
            $scheduledEnd = $this->scheduledEnd();
            $this->overtime_minutes = $this->clock_out->greaterThan($scheduledEnd)
                ? $scheduledEnd->diffInMinutes($this->clock_out)
                : 0;
        }

        $this->status = $this->late_minutes > 0 ? 'late' : 'present';
    }
}
