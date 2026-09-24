<x-app-layout>
    <x-slot name="header">Profile</x-slot>

    <div class="max-w-2xl space-y-6">
        <div class="card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card card-danger">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
