/**
 * Handles the chat thread (AJAX send + polling for new messages) and the
 * site-wide unread-message badge in the navigation bar.
 */

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

// ---- Conversation thread: send message via fetch, poll for new ones ----
const chatForm = document.getElementById('chat-form');
const chatMessages = document.getElementById('chat-messages');

function appendMessage(message) {
    const wrapper = document.createElement('div');
    wrapper.className = `flex ${message.from_me ? 'justify-end' : 'justify-start'}`;

    const bubble = document.createElement('div');
    bubble.className = `max-w-xs px-3 py-2 rounded-lg text-sm ${message.from_me ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-800'}`;

    const body = document.createElement('p');
    body.textContent = message.body;

    const time = document.createElement('p');
    time.className = 'text-[10px] mt-1 opacity-70';
    time.textContent = message.time;

    bubble.appendChild(body);
    bubble.appendChild(time);
    wrapper.appendChild(bubble);
    chatMessages.appendChild(wrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

async function pollMessages() {
    if (!chatMessages) return;

    const url = chatMessages.dataset.pollUrl;
    const afterId = chatMessages.dataset.lastId || 0;

    try {
        const response = await fetch(`${url}?after_id=${afterId}`, {
            headers: { Accept: 'application/json' },
        });
        const data = await response.json();

        data.messages.forEach((message) => {
            appendMessage(message);
            chatMessages.dataset.lastId = message.id;
        });
    } catch (err) {
        // Silently ignore transient network errors; next poll will retry.
    }
}

if (chatMessages) {
    chatMessages.scrollTop = chatMessages.scrollHeight;
    setInterval(pollMessages, 3000);
}

if (chatForm) {
    chatForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const input = document.getElementById('chat-body-input');
        const body = input.value.trim();
        if (!body) return;

        input.value = '';

        try {
            await fetch(chatForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    Accept: 'application/json',
                },
                body: new URLSearchParams({ body, _token: csrfToken() }),
            });
        } catch (err) {
            // If sending fails, restore the text so the user doesn't lose it.
            input.value = body;
        }

        pollMessages();
    });
}

// ---- Nav badge: total unread messages across all conversations ----
const chatBadges = document.querySelectorAll('.chat-unread-badge');
const chatBadgeUrl = document.body.dataset.chatUnreadUrl;

async function pollUnreadBadge() {
    if (!chatBadges.length || !chatBadgeUrl) return;

    try {
        const response = await fetch(chatBadgeUrl, { headers: { Accept: 'application/json' } });
        const data = await response.json();

        chatBadges.forEach((badge) => {
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    } catch (err) {
        // Ignore; try again on the next interval.
    }
}

if (chatBadges.length && chatBadgeUrl) {
    pollUnreadBadge();
    setInterval(pollUnreadBadge, 10000);
}
