<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = LeaveRequest::with(['user', 'reviewer'])->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $leaveRequests = $query->paginate(15)->withQueryString();

        return response()->json($leaveRequests);
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Leave request approved.',
            'leave_request' => $leaveRequest,
        ]);
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Leave request rejected.',
            'leave_request' => $leaveRequest,
        ]);
    }
}
