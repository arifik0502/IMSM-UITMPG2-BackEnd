<section>
    <header class="mb-4">
        <h2 class="font-semibold text-gray-800">Update Password</h2>
        <p class="text-sm text-gray-500">Use a long, random password to stay secure.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" value="Current Password" />
            <x-text-input id="current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="New Password" />
            <x-text-input id="password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm New Password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Save</x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-600">Saved.</p>
            @endif
        </div>
    </form>
</section>
