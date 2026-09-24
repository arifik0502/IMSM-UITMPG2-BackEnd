<x-guest-layout>
    <div class="mb-6">
        <a href="{{ route('home') }}" class="link link-accent">&larr; Back to home</a>
    </div>

    <h2 class="page-title mb-1">Guest Leave Application</h2>
    <p class="text-sm text-muted mb-6">No account needed &mdash; just fill in your details below.</p>

    @if (session('success'))
        <div class="alert-success mb-6" role="status">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('guest.leave.store') }}" class="space-y-4">
        @csrf

        <div class="field">
            <x-input-label for="guest_name" value="Full Name" />
            <x-text-input id="guest_name" type="text" name="guest_name" :value="old('guest_name')" required autofocus />
            <x-input-error :messages="$errors->get('guest_name')" />
        </div>

        <div class="field">
            <x-input-label for="guest_email" value="Email" />
            <x-text-input id="guest_email" type="email" name="guest_email" :value="old('guest_email')" required />
            <x-input-error :messages="$errors->get('guest_email')" />
        </div>

        <div class="field">
            <x-input-label for="type" value="Type" />
            <select id="type" name="type" required
                    class="form-field">
                <option value="annual" @selected(old('type') === 'annual')>Annual Leave</option>
                <option value="sick" @selected(old('type') === 'sick')>Sick Leave</option>
                <option value="unpaid" @selected(old('type') === 'unpaid')>Unpaid Leave</option>
                <option value="other" @selected(old('type') === 'other')>Other</option>
            </select>
            <x-input-error :messages="$errors->get('type')" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="field">
                <x-input-label for="start_date" value="Start Date" />
                <x-text-input id="start_date" type="date" name="start_date" :value="old('start_date')" required />
                <x-input-error :messages="$errors->get('start_date')" />
            </div>
            <div class="field">
                <x-input-label for="end_date" value="End Date" />
                <x-text-input id="end_date" type="date" name="end_date" :value="old('end_date')" required />
                <x-input-error :messages="$errors->get('end_date')" />
            </div>
        </div>

        <div class="field">
            <x-input-label for="reason" value="Reason (optional)" />
            <textarea id="reason" name="reason" rows="3"
                      class="form-field">{{ old('reason') }}</textarea>
            <x-input-error :messages="$errors->get('reason')" />
        </div>

        <x-primary-button class="w-full">Submit Application</x-primary-button>
    </form>
</x-guest-layout>
