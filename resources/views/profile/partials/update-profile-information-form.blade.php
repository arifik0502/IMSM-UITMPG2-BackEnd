<section>
    <header class="mb-4">
        <h2 class="font-semibold text-gray-800">Profile Information</h2>
        <p class="text-sm text-gray-500">Update your account's name and email address.</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Save</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-green-600">Saved.</p>
            @endif
        </div>
    </form>
</section>
