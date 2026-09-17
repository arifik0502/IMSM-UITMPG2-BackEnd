<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new employee account and issue an API token.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Verify credentials and issue an API token. Session-based Auth::attempt
     * isn't used here since this is a stateless token API consumed from a
     * separately-hosted frontend.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Revoke only the token used for this request, so other logged-in
     * devices/tabs for the same user stay signed in.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Send a 6-digit password reset code to the given email, mirroring the
     * original web app's forgot-password flow.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => [trans('passwords.user')],
            ]);
        }

        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user));

        return response()->json([
            'message' => 'We emailed you a 6-digit verification code.',
        ]);
    }

    /**
     * Verify the 6-digit code and set a new password.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();

        if (! $record) {
            throw ValidationException::withMessages([
                'code' => ['Please request a new verification code.'],
            ]);
        }

        $expiryMinutes = (int) config('auth.passwords.users.expire', 60);

        if (now()->diffInMinutes(\Carbon\Carbon::parse($record->created_at)) > $expiryMinutes) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            throw ValidationException::withMessages([
                'code' => ['This code has expired. Please request a new one.'],
            ]);
        }

        if (! Hash::check($validated['code'], $record->token)) {
            throw ValidationException::withMessages([
                'code' => ['That verification code is incorrect.'],
            ]);
        }

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => [trans('passwords.user')],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return response()->json([
            'message' => 'Your password has been reset. You can now log in.',
        ]);
    }

    public function googleRedirect()
    {
        return response()->json([
            'url' => \Laravel\Socialite\Facades\Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl(),
        ]);
    }

    public function googleCallback(\Illuminate\Http\Request $request)
        {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')
                ->stateless()
                ->user();

            $user = \App\Models\User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                'name' => $googleUser->getName(),
                'password' => \Illuminate\Support\Str::random(24),
                'email_verified_at' => now(),
            ]
        );

        if (! $user->google_id) {
        $user->update(['google_id' => $googleUser->getId()]);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Logged in via Google.',
        'user' => $user,
        'token' => $token,
    ]);
}
