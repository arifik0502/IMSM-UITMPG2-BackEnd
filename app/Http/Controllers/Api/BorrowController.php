<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BorrowController extends Controller
{
    /**
     * Public: live availability snapshot of all equipment. Available to
     * guests and logged-in employees alike.
     */
    public function equipment(Request $request)
    {
        $equipment = Equipment::where('is_active', true)
            ->orderBy('category')
            ->orderBy('code')
            ->get()
            ->map(function (Equipment $item) {
                $activeBorrow = $item->activeBorrow();

                return [
                    'id' => $item->id,
                    'code' => $item->code,
                    'category' => $item->category,
                    'available' => ! $activeBorrow,
                    'available_from' => $activeBorrow?->return_date?->format('d M Y'),
                ];
            });

        return response()->json(['equipment' => $equipment]);
    }

    /**
     * Public: create a booking. Works for both guests (no token — must
     * supply guest_name/guest_email) and logged-in employees (Bearer token
     * present — no guest fields needed). Not gated by auth:sanctum
     * middleware so guests can reach it; we resolve the user optionally.
     */
    public function store(Request $request)
    {
        $user = $request->user('sanctum');

        $rules = [
            'equipment_id' => ['required', 'exists:equipment,id'],
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after_or_equal:borrow_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];

        if (! $user) {
            $rules['guest_name'] = ['required', 'string', 'max:255'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
        }

        $validated = $request->validate($rules);

        $overlaps = BorrowRequest::overlapsFor(
            (int) $validated['equipment_id'],
            $validated['borrow_date'],
            $validated['return_date']
        );

        if ($overlaps) {
            throw ValidationException::withMessages([
                'equipment_id' => ['That equipment is already booked for an overlapping date range. Please choose another item or different dates.'],
            ]);
        }

        $borrowRequest = BorrowRequest::create([
            'equipment_id' => $validated['equipment_id'],
            'user_id' => $user?->id,
            'guest_name' => $validated['guest_name'] ?? null,
            'guest_email' => $validated['guest_email'] ?? null,
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking request submitted and is pending admin approval.',
            'borrow_request' => $borrowRequest,
        ], 201);
    }

    /**
     * Auth required: the logged-in employee's own borrow history.
     */
    public function history(Request $request)
    {
        $borrowRequests = $request->user()
            ->borrowRequests()
            ->with('equipment')
            ->orderByDesc('borrow_date')
            ->paginate(10);

        return response()->json($borrowRequests);
    }

    /**
     * Auth required: mark one of the current employee's own borrow records
     * as returned.
     */
    public function markReturned(Request $request, BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($borrowRequest->status !== 'approved') {
            throw ValidationException::withMessages(['status' => ['This booking has not been approved yet.']]);
        }

        if (! $borrowRequest->actual_returned_at) {
            $borrowRequest->update(['actual_returned_at' => now()]);
        }

        return response()->json([
            'message' => 'Marked as returned. Thanks!',
            'borrow_request' => $borrowRequest,
        ]);
    }
}
