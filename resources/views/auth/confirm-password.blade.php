<x-guest-layout>
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm your password</h2>

    <p class="text-sm text-gray-600 mb-6">
        This is a secure area. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">Confirm</x-primary-button>
    </form>
</x-guest-layout>
