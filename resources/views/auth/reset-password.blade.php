<x-guest-layout>

    <h2 class="text-xl font-semibold text-gray-800 mb-1">Enter verification code</h2>

    <p class="text-sm text-gray-500 mb-6">
        We emailed a 6-digit code to your address. Enter it below along with your new password.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />

            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email', $email)"
                required
                autofocus
                autocomplete="username"
                class="mt-1 block w-full"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="code" value="Verification Code" />

            <x-text-input
                id="code"
                type="text"
                name="code"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="6"
                placeholder="123456"
                autocomplete="one-time-code"
                required
                class="mt-1 block w-full tracking-[0.5em] text-center font-semibold"
            />

            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="New Password" />

            <div class="relative mt-1">
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="block w-full pr-12"
                />

                <button
                    type="button"
                    onclick="togglePassword('password', 'eye-password', 'eye-off-password')"
                    class="absolute right-0 top-0 h-full flex items-center justify-center px-3 text-gray-500 hover:text-gray-700"
                    aria-label="Show password"
                >
                    <svg
                        id="eye-password"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>

                    <svg
                        id="eye-off-password"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 hidden"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3l18 18"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10.584 10.587a2 2 0 002.829 2.829"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.88 5.09A9.97 9.97 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.132 5.411M6.228 6.228A10.05 10.05 0 002.458 12c1.274 4.057 5.064 7 9.542 7a9.97 9.97 0 003.12-.495"
                        />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm New Password" />

            <div class="relative mt-1">
                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="block w-full pr-12"
                />

                <button
                    type="button"
                    onclick="togglePassword('password_confirmation', 'eye-confirm', 'eye-off-confirm')"
                    class="absolute right-0 top-0 h-full flex items-center justify-center px-3 text-gray-500 hover:text-gray-700"
                    aria-label="Show password"
                >
                    <svg
                        id="eye-confirm"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>

                    <svg
                        id="eye-off-confirm"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 hidden"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3l18 18"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10.584 10.587a2 2 0 002.829 2.829"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.88 5.09A9.97 9.97 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.132 5.411M6.228 6.228A10.05 10.05 0 002.458 12c1.274 4.057 5.064 7 9.542 7a9.97 9.97 0 003.12-.495"
                        />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Reset Password
        </x-primary-button>

        <p class="text-sm text-center text-gray-600">
            Didn't get a code?
            <a href="{{ route('password.request') }}" class="text-brand-600 hover:underline">
                Request a new one
            </a>
        </p>
    </form>

    <script>
        function togglePassword(inputId, eyeId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);

            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }
    </script>

</x-guest-layout>