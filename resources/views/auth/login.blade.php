<x-guest-layout>

    <h2 class="text-xl font-semibold text-gray-800 mb-6">
        Sign in to your account
    </h2>

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">

        @csrf

        <div>
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
                class="mt-1 block w-full"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        <div>
            <x-input-label
                for="password"
                value="Password"
            />

            <div class="relative mt-1">

                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="block w-full pr-10"
                />

                <button
                    type="button"
                    id="togglePassword"
                    class="absolute right-0 top-0 h-full flex items-center justify-center px-3 text-gray-500 hover:text-gray-900 focus:outline-none"
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
                class="mt-2"
            />
        </div>

        <div class="flex items-center justify-between">

            <label class="flex items-center gap-2 text-sm text-gray-600">

                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                >

                <span>Remember me</span>

            </label>

            @if (Route::has('password.request'))

                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-brand-600 hover:underline"
                >
                    Forgot password?
                </a>

            @endif

        </div>

          <x-primary-button class="w-full">Log in</x-primary-button>

        <div class="relative py-2">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-2 text-gray-400">or</span></div>
        </div>


        <p class="text-sm text-center text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-brand-600 hover:underline">Register</a>
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

