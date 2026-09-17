<section>
    <header class="mb-4">
        <h2 class="font-semibold text-gray-800">Delete Account</h2>
        <p class="text-sm text-gray-500">Once your account is deleted, all of its attendance and leave records will be permanently removed.</p>
    </header>

    <form method="POST" action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');"
          class="space-y-4">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="password_delete" value="Password" />
            <x-text-input id="password_delete" name="password" type="password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-danger-button>Delete Account</x-danger-button>
    </form>
</section>
