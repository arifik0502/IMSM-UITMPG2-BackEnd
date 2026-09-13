<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BorrowController extends Controller
{
    /**
     * Show the borrow form (available to both guests and logged-in employees)
     * along with a live availability snapshot of all equipment.
     */
    public function create(Request $request)
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

        return view('borrow.create', [
            'equipmentList' => $equipment,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'equipment_id' => ['required', 'exists:equipment,id'],
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after_or_equal:borrow_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];

        if (! $request->user()) {
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
                'equipment_id' => 'That equipment is already booked for an overlapping date range. Please choose another item or different dates.',
            ]);
        }

        BorrowRequest::create([
            'equipment_id' => $validated['equipment_id'],
            'user_id' => $request->user()?->id,
            'guest_name' => $validated['guest_name'] ?? null,
            'guest_email' => $validated['guest_email'] ?? null,
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Booking request submitted and is pending admin approval.');
    }

    /**
     * Logged-in employee's own borrow history.
     */
    public function history(Request $request)
    {
        $borrowRequests = $request->user()
            ->borrowRequests()
            ->with('equipment')
            ->orderByDesc('borrow_date')
            ->paginate(10);

        return view('borrow.history', [
            'borrowRequests' => $borrowRequests,
        ]);
    }

    /**
     * Mark one of the current employee's own borrow records as returned.
     */
    public function markReturned(Request $request, BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($borrowRequest->status !== 'approved') {
            return back()->with('error', 'This booking has not been approved yet.');
        }

        if (! $borrowRequest->actual_returned_at) {
            $borrowRequest->update(['actual_returned_at' => now()]);
        }

        return back()->with('success', 'Marked as returned. Thanks!');
    }
}
