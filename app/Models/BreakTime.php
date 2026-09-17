<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;

    protected $table = 'breaks';

    protected $fillable = [
        'attendance_id',
        'break_start',
        'break_end',
    ];

    protected function casts(): array
    {
        return [
            'break_start' => 'datetime',
            'break_end' => 'datetime',
        ];
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function durationInMinutes(): ?int
    {
        if (! $this->break_start || ! $this->break_end) {
            return null;
        }

        return $this->break_start->diffInMinutes($this->break_end);
    }
}
