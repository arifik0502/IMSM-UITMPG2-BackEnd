<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BreakController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestLeaveController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'dashboard');
    }

    return view('welcome');
})->name('home');

// ---- Guest-accessible routes (no login required) ----
Route::get('/guest/leave', [GuestLeaveController::class, 'create'])->name('guest.leave.create');
Route::post('/guest/leave', [GuestLeaveController::class, 'store'])->name('guest.leave.store');

Route::get('/borrow', [BorrowController::class, 'create'])->name('borrow.create');
Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');

    Route::post('/breaks/start', [BreakController::class, 'start'])->name('breaks.start');
    Route::post('/breaks/end', [BreakController::class, 'end'])->name('breaks.end');

    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');

    Route::get('/borrow/history', [BorrowController::class, 'history'])->name('borrow.history');
    Route::post('/borrow/{borrowRequest}/return', [BorrowController::class, 'markReturned'])->name('borrow.return');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{user}/poll', [ChatController::class, 'poll'])->name('chat.poll');
    Route::get('/chat-unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread-count');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---- Admin area ----
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/employees', [\App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/{employee}', [\App\Http\Controllers\Admin\EmployeeController::class, 'show'])->name('employees.show');

    Route::get('/leave', [\App\Http\Controllers\Admin\LeaveController::class, 'index'])->name('leave.index');
    Route::post('/leave/{leaveRequest}/approve', [\App\Http\Controllers\Admin\LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{leaveRequest}/reject', [\App\Http\Controllers\Admin\LeaveController::class, 'reject'])->name('leave.reject');

    Route::get('/borrow', [\App\Http\Controllers\Admin\BorrowController::class, 'index'])->name('borrow.index');
    Route::post('/borrow/{borrowRequest}/approve', [\App\Http\Controllers\Admin\BorrowController::class, 'approve'])->name('borrow.approve');
    Route::post('/borrow/{borrowRequest}/reject', [\App\Http\Controllers\Admin\BorrowController::class, 'reject'])->name('borrow.reject');
    Route::post('/borrow/{borrowRequest}/return', [\App\Http\Controllers\Admin\BorrowController::class, 'markReturned'])->name('borrow.return');
});

require __DIR__.'/auth.php';


