<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', [
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $record) {
            throw ValidationException::withMessages([
                'code' => 'Please request a new verification code.',
            ]);
        }

        $expiryMinutes = (int) config('auth.passwords.users.expire', 60);

        if (now()->diffInMinutes(\Carbon\Carbon::parse($record->created_at)) > $expiryMinutes) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            throw ValidationException::withMessages([
                'code' => 'This code has expired. Please request a new one.',
            ]);
        }

        if (! Hash::check($request->code, $record->token)) {
            throw ValidationException::withMessages([
                'code' => 'That verification code is incorrect.',
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => trans('passwords.user'),
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset. You can now log in.');
    }
}
