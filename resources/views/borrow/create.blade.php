@auth
    <x-app-layout>
        <x-slot name="header">Borrow Equipment</x-slot>

        <div class="mb-6">
            <a href="{{ route('borrow.history') }}" class="link link-accent">View my borrow history &rarr;</a>
        </div>

        @include('borrow._content')
    </x-app-layout>
@else
    <x-public-wide-layout>
        <div class="mb-6">
            <a href="{{ route('home') }}" class="link link-accent">&larr; Back to home</a>
        </div>

        <h2 class="page-title mb-1">Borrow Equipment</h2>
        <p class="text-sm text-muted mb-6">Open to guests too &mdash; just fill in your name and email below.</p>

        @include('borrow._content')
    </x-public-wide-layout>
@endauth
