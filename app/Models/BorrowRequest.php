<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'user_id',
        'guest_name',
        'guest_email',
        'borrow_date',
        'return_date',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'actual_returned_at',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_date' => 'date',
            'reviewed_at' => 'datetime',
            'actual_returned_at' => 'datetime',
        ];
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function borrowerName(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Unknown';
    }

    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    /**
     * The lifecycle state of an approved booking: returned / overdue / active.
     * Only meaningful once status = 'approved'; use the `status` column
     * itself to check pending/rejected.
     */
    public function returnStatus(): string
    {
        if ($this->actual_returned_at) {
            return 'returned';
        }

        return Carbon::today()->greaterThan($this->return_date) ? 'overdue' : 'active';
    }

    /**
     * Find overlapping bookings for a given piece of equipment that are still
     * "live" (pending or approved, and not yet returned) so a slot can't be
     * double-booked while awaiting an admin decision.
     */
    public static function overlapsFor(int $equipmentId, string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        return static::query()
            ->where('equipment_id', $equipmentId)
            ->whereIn('status', ['pending', 'approved'])
            ->whereNull('actual_returned_at')
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where('borrow_date', '<=', $endDate)
            ->where('return_date', '>=', $startDate)
            ->exists();
    }
}
