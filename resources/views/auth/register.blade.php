<x-guest-layout>

    <h2 class="page-title mb-6">
        Create your account
    </h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">

        @csrf

        {{-- Name --}}
        <div class="field">
            <x-input-label
                for="name"
                value="Name"
            />

            <x-text-input
                id="name"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error
                :messages="$errors->get('name')"
            />
        </div>

        {{-- Email --}}
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
                autocomplete="username"
            />

            <x-input-error
                :messages="$errors->get('email')"
            />
        </div>

        {{-- Password --}}
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
                    autocomplete="new-password"
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

        {{-- Confirm Password --}}
        <div class="field">
            <x-input-label
                for="password_confirmation"
                value="Confirm Password"
            />

            <div class="input-wrap">

                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="pr-11"
                />

                <button
                    type="button"
                    id="togglePasswordConfirmation"
                    class="input-toggle"
                    aria-label="Show password"
                >
                    <svg
                        id="showConfirmationIcon"
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
                        id="hideConfirmationIcon"
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
                :messages="$errors->get('password_confirmation')"
            />
        </div>

        {{-- Register Button --}}
        <x-primary-button class="w-full">
            Register
        </x-primary-button>

        {{-- Login Link --}}
        <p class="text-center text-sm text-muted">

            Already registered?

            <a
                href="{{ route('login') }}"
                class="link link-accent"
            >
                Log in
            </a>

        </p>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const password = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const showPasswordIcon = document.getElementById('showPasswordIcon');
            const hidePasswordIcon = document.getElementById('hidePasswordIcon');

            togglePassword.addEventListener('click', function () {

                const isHidden = password.type === 'password';

                password.type = isHidden ? 'text' : 'password';

                showPasswordIcon.classList.toggle('hidden', isHidden);
                hidePasswordIcon.classList.toggle('hidden', !isHidden);

                togglePassword.setAttribute(
                    'aria-label',
                    isHidden ? 'Hide password' : 'Show password'
                );

            });


            const passwordConfirmation =
                document.getElementById('password_confirmation');

            const togglePasswordConfirmation =
                document.getElementById('togglePasswordConfirmation');

            const showConfirmationIcon =
                document.getElementById('showConfirmationIcon');

            const hideConfirmationIcon =
                document.getElementById('hideConfirmationIcon');

            togglePasswordConfirmation.addEventListener('click', function () {

                const isHidden =
                    passwordConfirmation.type === 'password';

                passwordConfirmation.type =
                    isHidden ? 'text' : 'password';

                showConfirmationIcon.classList.toggle(
                    'hidden',
                    isHidden
                );

                hideConfirmationIcon.classList.toggle(
                    'hidden',
                    !isHidden
                );

                togglePasswordConfirmation.setAttribute(
                    'aria-label',
                    isHidden ? 'Hide password' : 'Show password'
                );

            });

        });
    </script>

</x-guest-layout>
