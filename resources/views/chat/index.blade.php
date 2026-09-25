<x-app-layout>
    <x-slot name="header">Chat</x-slot>

    <div class="card card-flush overflow-hidden">
        <div class="chat-shell">
            {{-- Contacts sidebar: full width on mobile when no thread is open, hidden once one is --}}
            <div class="{{ $activeContact ? 'hidden' : 'block' }} md:block chat-sidebar max-h-[70vh]">
                <div class="p-3 border-b border-gray-100">
                    <input type="text" id="contact-search" placeholder="Search contacts..." class="form-field text-sm w-full">
                </div>
                @forelse ($contacts as $contact)
                    <a href="{{ route('chat.show', $contact['user']) }}"
                       class="chat-contact"
                       data-name="{{ strtolower($contact['user']->name) }}"
                       @if ($activeContact && $activeContact->id === $contact['user']->id) aria-current="page" @endif>
                        <div class="min-w-0">
                            <p class="text-strong truncate text-sm font-medium">{{ $contact['user']->name }}</p>
                            <p class="text-muted truncate text-xs">{{ $contact['last_message'] ?? 'No messages yet' }}</p>
                        </div>
                        @if ($contact['unread_count'] > 0)
                            <span class="badge-success shrink-0">{{ $contact['unread_count'] }}</span>
                        @endif
                    </a>
                @empty
                    <p class="empty-state">No other employees to chat with yet.</p>
                @endforelse
            </div>

            {{-- Conversation thread: hidden on mobile until a contact is selected, always visible from md: up --}}
            <div class="{{ $activeContact ? 'flex' : 'hidden' }} md:flex md:col-span-2 flex-col min-h-0">
                @if ($activeContact)
                    <div class="chat-thread-head">
                        <a href="{{ route('chat.index') }}" class="icon-btn md:hidden" aria-label="Back to contacts">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <p class="text-strong font-medium">{{ $activeContact->name }}</p>
                    </div>

                    <div class="px-4 py-2 border-b border-gray-100">
                        <input type="text" id="message-search" placeholder="Search messages..." class="form-field text-sm w-full">
                    </div>

                    <div id="chat-messages"
                         data-poll-url="{{ route('chat.poll', $activeContact) }}"
                         data-last-id="{{ $messages->last()->id ?? 0 }}"
                         class="chat-messages max-h-[60vh] md:max-h-none">
                        @foreach ($messages as $message)
                            <div class="chat-message flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="chat-bubble {{ $message->sender_id === auth()->id() ? 'chat-bubble-me' : 'chat-bubble-them' }}">
                                    <p>{{ $message->body }}</p>
                                    <p class="chat-time">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form id="chat-form" method="POST" action="{{ route('chat.store', $activeContact) }}" class="chat-composer">
                        @csrf
                        <input type="text" name="body" id="chat-body-input" required maxlength="2000" autocomplete="off"
                               placeholder="Type a message..."
                               class="form-field flex-1">
                        <button type="submit" class="btn-primary shrink-0">Send</button>
                    </form>
                @else
                    <div class="empty-state hidden flex-1 items-center justify-center p-8 md:flex">
                        Select a colleague on the left to start chatting.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
