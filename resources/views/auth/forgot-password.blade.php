<x-guest-layout>
    <h2 class="page-title mb-4">Forgot your password?</h2>

    <p class="text-sm text-muted mb-6">
        No problem. Enter your email and we'll send you a 6-digit verification code.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div class="field">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button class="w-full">Send Verification Code</x-primary-button>
    </form>
</x-guest-layout>
