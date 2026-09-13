<?php

use App\Http\Controllers\Api\Admin\BorrowController as AdminBorrowController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Api\Admin\LeaveController as AdminLeaveController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BorrowController;
use App\Http\Controllers\Api\BreakController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\GuestLeaveController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// ---- Public (no token required) ----
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::post('/guest/leave', [GuestLeaveController::class, 'store']);

Route::get('/borrow/equipment', [BorrowController::class, 'equipment']);
Route::post('/borrow', [BorrowController::class, 'store']); // works for guests AND logged-in users

// ---- Authenticated (Authorization: Bearer <token>) ----
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/attendance/status', [AttendanceController::class, 'status']);
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut']);
    Route::get('/attendance/history', [AttendanceController::class, 'history']);

    Route::post('/breaks/start', [BreakController::class, 'start']);
    Route::post('/breaks/end', [BreakController::class, 'end']);

    Route::get('/leave', [LeaveController::class, 'index']);
    Route::post('/leave', [LeaveController::class, 'store']);

    Route::get('/borrow/history', [BorrowController::class, 'history']);
    Route::post('/borrow/{borrowRequest}/return', [BorrowController::class, 'markReturned']);

    Route::get('/chat', [ChatController::class, 'index']);
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount']);
    Route::get('/chat/{user}', [ChatController::class, 'show']);
    Route::post('/chat/{user}', [ChatController::class, 'store']);
    Route::get('/chat/{user}/poll', [ChatController::class, 'poll']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
});

// ---- Admin only ----
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    Route::get('/employees', [AdminEmployeeController::class, 'index']);
    Route::get('/employees/{employee}', [AdminEmployeeController::class, 'show']);

    Route::get('/leave', [AdminLeaveController::class, 'index']);
    Route::post('/leave/{leaveRequest}/approve', [AdminLeaveController::class, 'approve']);
    Route::post('/leave/{leaveRequest}/reject', [AdminLeaveController::class, 'reject']);

    Route::get('/borrow', [AdminBorrowController::class, 'index']);
    Route::post('/borrow/{borrowRequest}/approve', [AdminBorrowController::class, 'approve']);
    Route::post('/borrow/{borrowRequest}/reject', [AdminBorrowController::class, 'reject']);
    Route::post('/borrow/{borrowRequest}/return', [AdminBorrowController::class, 'markReturned']);
});
