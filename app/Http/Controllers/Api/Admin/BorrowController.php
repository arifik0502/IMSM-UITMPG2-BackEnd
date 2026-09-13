<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = BorrowRequest::with(['equipment', 'user'])->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $borrowRequests = $query->paginate(15)->withQueryString();

        return response()->json($borrowRequests);
    }

    public function approve(Request $request, BorrowRequest $borrowRequest)
    {
        $overlaps = BorrowRequest::query()
            ->where('equipment_id', $borrowRequest->equipment_id)
            ->where('status', 'approved')
            ->whereNull('actual_returned_at')
            ->where('id', '!=', $borrowRequest->id)
            ->where('borrow_date', '<=', $borrowRequest->return_date)
            ->where('return_date', '>=', $borrowRequest->borrow_date)
            ->exists();

        if ($overlaps) {
            throw ValidationException::withMessages([
                'equipment_id' => ['Cannot approve — this equipment already has an approved booking for an overlapping date range.'],
            ]);
        }

        $borrowRequest->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Booking approved.',
            'borrow_request' => $borrowRequest,
        ]);
    }

    public function reject(Request $request, BorrowRequest $borrowRequest)
    {
        $borrowRequest->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Booking rejected.',
            'borrow_request' => $borrowRequest,
        ]);
    }

    /**
     * Admin override: mark any approved booking as returned (e.g. on behalf
     * of a guest, since guests have no login to return through themselves).
     */
    public function markReturned(Request $request, BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->status !== 'approved') {
            throw ValidationException::withMessages([
                'status' => ['Only approved bookings can be marked as returned.'],
            ]);
        }

        if (! $borrowRequest->actual_returned_at) {
            $borrowRequest->update(['actual_returned_at' => now()]);
        }

        return response()->json([
            'message' => 'Marked as returned.',
            'borrow_request' => $borrowRequest,
        ]);
    }
}
