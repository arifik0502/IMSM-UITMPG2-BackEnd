<x-guest-layout>
    <h2 class="page-title mb-4">Confirm your password</h2>

    <p class="text-sm text-muted mb-6">
        This is a secure area. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div class="field">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" autofocus />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button class="w-full">Confirm</x-primary-button>
    </form>
</x-guest-layout>
