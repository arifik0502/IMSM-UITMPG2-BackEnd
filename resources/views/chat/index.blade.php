<x-app-layout>
    <x-slot name="header">Chat</x-slot>

    <div class="card p-0 overflow-hidden">
        <div class="grid grid-cols-1 sm:grid-cols-3 sm:h-[32rem]">
            {{-- Contacts sidebar: full width on mobile when no thread is open, hidden once one is --}}
            <div class="{{ $activeContact ? 'hidden' : 'block' }} sm:block sm:col-span-1 border-r border-gray-200 overflow-y-auto max-h-[70vh] sm:max-h-none">
                @forelse ($contacts as $contact)
                    <a href="{{ route('chat.show', $contact['user']) }}"
                       class="flex items-center justify-between gap-2 px-4 py-3.5 sm:py-3 border-b border-gray-100 active:bg-gray-100 hover:bg-gray-50 {{ $activeContact && $activeContact->id === $contact['user']->id ? 'bg-brand-50' : '' }}">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $contact['user']->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $contact['last_message'] ?? 'No messages yet' }}</p>
                        </div>
                        @if ($contact['unread_count'] > 0)
                            <span class="badge-green shrink-0">{{ $contact['unread_count'] }}</span>
                        @endif
                    </a>
                @empty
                    <p class="text-sm text-gray-400 p-4">No other employees to chat with yet.</p>
                @endforelse
            </div>

            {{-- Conversation thread: hidden on mobile until a contact is selected, always visible from sm: up --}}
            <div class="{{ $activeContact ? 'flex' : 'hidden' }} sm:flex sm:col-span-2 flex-col min-h-0">
                @if ($activeContact)
                    <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                        <a href="{{ route('chat.index') }}" class="sm:hidden text-gray-400 hover:text-gray-600" aria-label="Back to contacts">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <p class="font-medium text-gray-800">{{ $activeContact->name }}</p>
                    </div>

                    <div id="chat-messages"
                         data-poll-url="{{ route('chat.poll', $activeContact) }}"
                         data-last-id="{{ $messages->last()->id ?? 0 }}"
                         class="flex-1 overflow-y-auto p-4 space-y-3 max-h-[60vh] sm:max-h-none">
                        @foreach ($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[85%] sm:max-w-xs px-3 py-2 rounded-lg text-sm {{ $message->sender_id === auth()->id() ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                                    <p class="break-words">{{ $message->body }}</p>
                                    <p class="text-[10px] mt-1 opacity-70">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form id="chat-form" method="POST" action="{{ route('chat.store', $activeContact) }}" class="border-t border-gray-200 p-3 flex gap-2">
                        @csrf
                        <input type="text" name="body" id="chat-body-input" required maxlength="2000" autocomplete="off"
                               placeholder="Type a message..."
                               class="flex-1 form-field">
                        <button type="submit" class="btn-primary shrink-0">Send</button>
                    </form>
                @else
                    <div class="flex-1 items-center justify-center text-gray-400 text-sm p-8 text-center hidden sm:flex">
                        Select a colleague on the left to start chatting.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>