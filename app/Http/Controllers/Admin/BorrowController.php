<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;

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

        return view('admin.borrow.index', [
            'borrowRequests' => $borrowRequests,
            'status' => $status,
        ]);
    }

    public function approve(Request $request, BorrowRequest $borrowRequest)
    {
        // Guard against approving two overlapping bookings for the same item.
        $overlaps = BorrowRequest::query()
            ->where('equipment_id', $borrowRequest->equipment_id)
            ->where('status', 'approved')
            ->whereNull('actual_returned_at')
            ->where('id', '!=', $borrowRequest->id)
            ->where('borrow_date', '<=', $borrowRequest->return_date)
            ->where('return_date', '>=', $borrowRequest->borrow_date)
            ->exists();

        if ($overlaps) {
            return back()->with('error', 'Cannot approve — this equipment already has an approved booking for an overlapping date range.');
        }

        $borrowRequest->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Booking approved.');
    }

    public function reject(Request $request, BorrowRequest $borrowRequest)
    {
        $borrowRequest->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Booking rejected.');
    }

    /**
     * Admin override: mark any approved booking as returned (e.g. on behalf of a guest).
     */
    public function markReturned(Request $request, BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->status !== 'approved') {
            return back()->with('error', 'Only approved bookings can be marked as returned.');
        }

        if (! $borrowRequest->actual_returned_at) {
            $borrowRequest->update(['actual_returned_at' => now()]);
        }

        return back()->with('success', 'Marked as returned.');
    }
}
