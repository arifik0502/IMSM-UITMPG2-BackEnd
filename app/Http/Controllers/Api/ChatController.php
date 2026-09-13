<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * All other employees with last-message preview and unread counts.
     */
    public function index(Request $request)
    {
        return response()->json([
            'contacts' => $this->contactsFor($request->user()),
        ]);
    }

    /**
     * Conversation thread with a specific employee. Marks their messages
     * to us as read as a side effect, same as the original web app.
     */
    public function show(Request $request, User $user)
    {
        $me = $request->user();

        abort_if($user->id === $me->id, 404);

        ChatMessage::where('sender_id', $user->id)
            ->where('recipient_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::between($me->id, $user->id)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'contact' => $user,
            'messages' => $messages->map(fn ($m) => [
                'id' => $m->id,
                'from_me' => $m->sender_id === $me->id,
                'body' => $m->body,
                'time' => $m->created_at->format('h:i A'),
                'created_at' => $m->created_at,
            ]),
        ]);
    }

    public function store(Request $request, User $user)
    {
        $me = $request->user();

        abort_if($user->id === $me->id, 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = ChatMessage::create([
            'sender_id' => $me->id,
            'recipient_id' => $user->id,
            'body' => $validated['body'],
        ]);

        return response()->json([
            'id' => $message->id,
            'from_me' => true,
            'body' => $message->body,
            'time' => $message->created_at->format('h:i A'),
            'created_at' => $message->created_at,
        ], 201);
    }

    /**
     * Polling endpoint: new messages in this thread since a given message id.
     * The frontend calls this every few seconds instead of a websocket.
     */
    public function poll(Request $request, User $user)
    {
        $me = $request->user();
        $afterId = (int) $request->query('after_id', 0);

        ChatMessage::where('sender_id', $user->id)
            ->where('recipient_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::between($me->id, $user->id)
            ->where('id', '>', $afterId)
            ->orderBy('created_at')
            ->get(['id', 'sender_id', 'body', 'created_at']);

        return response()->json([
            'messages' => $messages->map(fn ($m) => [
                'id' => $m->id,
                'from_me' => $m->sender_id === $me->id,
                'body' => $m->body,
                'time' => $m->created_at->format('h:i A'),
            ]),
        ]);
    }

    /**
     * Total unread messages for the current user, for a nav badge.
     */
    public function unreadCount(Request $request)
    {
        $count = ChatMessage::where('recipient_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    private function contactsFor(User $me)
    {
        return User::where('id', '!=', $me->id)
            ->orderBy('name')
            ->get()
            ->map(function (User $contact) use ($me) {
                $lastMessage = ChatMessage::between($me->id, $contact->id)
                    ->latest('created_at')
                    ->first();

                return [
                    'user' => $contact,
                    'last_message' => $lastMessage?->body,
                    'last_message_at' => $lastMessage?->created_at,
                    'unread_count' => ChatMessage::where('sender_id', $contact->id)
                        ->where('recipient_id', $me->id)
                        ->whereNull('read_at')
                        ->count(),
                ];
            })
            ->sortByDesc(fn ($c) => $c['last_message_at'] ?? $c['user']->created_at)
            ->values();
    }
}
