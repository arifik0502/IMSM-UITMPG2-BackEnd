<x-guest-layout>

    <h2 class="page-title mb-6">
        Sign in to your account
    </h2>

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">

        @csrf

        <div class="field">
            <x-input-label
                for="email"
                value="Email"
            />

            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error
                :messages="$errors->get('email')"
            />
        </div>

        <div class="field">
            <x-input-label
                for="password"
                value="Password"
            />

            <div class="input-wrap">

                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="pr-11"
                />

                <button
                    type="button"
                    id="togglePassword"
                    class="input-toggle"
                    aria-label="Show password"
                >

                    <svg
                        id="showPasswordIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.035.105.035.221 0 .326C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>

                    <svg
                        id="hidePasswordIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="hidden h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.174 19 12 19c4.826 0 8.774-2.662 10.066-7a10.477 10.477 0 00-2.046-3.777"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.228 6.228A10.45 10.45 0 0112 5c4.826 0 8.774 2.662 10.066 7a10.45 10.45 0 01-4.17 5.14"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.228 6.228L3 3"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.228 6.228l3.086 3.086"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.686 14.686L21 21"
                        />
                    </svg>

                </button>

            </div>

            <x-input-error
                :messages="$errors->get('password')"
            />
        </div>

        <div class="flex items-center justify-between">

            <label class="check-label">

                <input
                    type="checkbox"
                    name="remember"
                >

                <span>Remember me</span>

            </label>

            @if (Route::has('password.request'))

                <a
                    href="{{ route('password.request') }}"
                    class="link link-accent"
                >
                    Forgot password?
                </a>

            @endif

        </div>

          <x-primary-button class="w-full">Log in</x-primary-button>

        <div class="divider"><span>or</span></div>

    <a href="{{ route('google.redirect') }}"
        class="btn-secondary w-full">
        <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="currentColor" d="M21.35 11.1h-9.17v2.73h6.51c-.33 3.81-3.5 5.44-6.5 5.44C8.36 19.27 5 16.25 5 12c0-4.1 3.2-7.27 7.2-7.27 3.09 0 4.9 1.97 4.9 1.97L19 4.72S16.56 2 12.1 2C6.42 2 2.03 6.8 2.03 12c0 5.05 4.13 10 10.22 10 5.35 0 9.25-3.67 9.25-9.09 0-1.15-.15-1.81-.15-1.81Z"/></svg>
        continue with Google
    </a>
                
         <div class="divider"><span>or</span></div>


        <p class="text-sm text-center text-muted">
            Don't have an account?
            <a href="{{ route('register') }}" class="link link-accent">Register</a>
        </p>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const password = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const showIcon = document.getElementById('showPasswordIcon');
            const hideIcon = document.getElementById('hidePasswordIcon');

            togglePassword.addEventListener('click', function () {

                const isHidden = password.type === 'password';

                password.type = isHidden ? 'text' : 'password';

                showIcon.classList.toggle('hidden', isHidden);
                hideIcon.classList.toggle('hidden', !isHidden);

                togglePassword.setAttribute(
                    'aria-label',
                    isHidden ? 'Hide password' : 'Show password'
                );

            });

        });
    </script>

</x-guest-layout>

