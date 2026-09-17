<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'code',
        'category',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    /**
     * Whether this equipment is currently out on loan or reserved (pending/approved, not yet returned).
     */
    public function isCurrentlyBorrowed(): bool
    {
        return $this->borrowRequests()->whereIn('status', ['pending', 'approved'])->whereNull('actual_returned_at')->exists();
    }

    /**
     * The active (pending/approved, unreturned) borrow record for this equipment, if any.
     */
    public function activeBorrow()
    {
        return $this->borrowRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->whereNull('actual_returned_at')
            ->orderByDesc('borrow_date')
            ->first();
    }

    /**
     * Check whether this equipment is free for the given date range.
     */
    public function isAvailableFor(string $startDate, string $endDate, ?int $excludeBorrowRequestId = null): bool
    {
        return ! $this->borrowRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->whereNull('actual_returned_at')
            ->when($excludeBorrowRequestId, fn ($q) => $q->where('id', '!=', $excludeBorrowRequestId))
            ->where('borrow_date', '<=', $endDate)
            ->where('return_date', '>=', $startDate)
            ->exists();
    }
}
