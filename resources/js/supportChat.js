/* -------------------- CHAT SERVICE -------------------- */
class ChatService {
    constructor() {
        this.AUTH_USER = null;
        this.AUTH_USER_ID = null;

        this.activeThreadId = null;
        this.currentChannel = null;

        this.loadedMessageIds = new Set();
        this.lastMessageId = null;

        this.ui = {};
    }

    /* -------------------- INIT -------------------- */

    async init() {
        await this.fetchAuthUser();
        this.cacheDom();
        this.bindUI();
    }

    cacheDom() {
        this.ui.chatToggle = document.getElementById('chat-toggle');
        this.ui.chatModal = document.getElementById('supportChatModal');
        this.ui.chatClose = document.querySelector('.support_chat_modal_close');

        this.ui.threadList = document.querySelector('.support_chat_sidebar');
        this.ui.messages = document.querySelector('.support_chat_messages');

        this.ui.textarea = document.querySelector('.support_chat_textarea');
        this.ui.sendBtn = document.querySelector('.support_chat_send_btn');
    }

    bindUI() {
        this.ui.chatToggle.onclick = () => {
            this.ui.chatModal.classList.toggle('support_chat_hidden');
            if (!this.ui.chatModal.classList.contains('support_chat_hidden')) {
                this.fetchThreads();
            } else {
                this.leaveChannel();
            }
        };

        this.ui.chatClose.onclick = () => {
            this.ui.chatModal.classList.add('support_chat_hidden');
            this.leaveChannel();
        };

        this.ui.sendBtn.onclick = () => this.sendMessage();

        this.ui.threadList.addEventListener('click', e => {
            const ticket = e.target.closest('.support_chat_ticket');
            if (!ticket) {
                this.clearMessages();
                this.leaveChannel();
            }
        });
    }

    /* -------------------- AUTH -------------------- */

    async fetchAuthUser() {
        const res = await fetch('/api/me', {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + localStorage.getItem('sanctum_token'),
            },
        });

        const user = await res.json();
        this.AUTH_USER = user;
        this.AUTH_USER_ID = user.id;
    }

    /* -------------------- THREADS -------------------- */

    async fetchThreads() {
        const res = await fetch('/api/support/threads', {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + localStorage.getItem('sanctum_token'),
            },
        });

        const threads = await res.json();

        this.ui.threadList
            .querySelectorAll('.support_chat_ticket:not(:first-child)')
            .forEach(el => el.remove());

        threads.forEach(thread => {
            const div = document.createElement('div');
            div.className = 'support_chat_ticket';
            div.innerHTML = `
                <div class="support_chat_avatar">${thread.title.charAt(0)}</div>
                <div class="support_chat_ticket_info">
                    <strong>${thread.title}</strong>
                    <p>${thread.status}</p>
                </div>
            `;

            div.onclick = () => this.openThread(thread.id, div);
            this.ui.threadList.appendChild(div);
        });
    }

    /* -------------------- THREAD OPEN -------------------- */

    openThread(threadId, el) {
        document
            .querySelectorAll('.support_chat_ticket')
            .forEach(t => t.classList.remove('support_chat_ticket_active'));

        el.classList.add('support_chat_ticket_active');

        this.leaveChannel();
        this.clearMessages();

        this.activeThreadId = threadId;
        this.subscribeToThread(threadId);
        this.loadMessages();
    }

    /* -------------------- REALTIME -------------------- */

    subscribeToThread(threadId) {
        this.currentChannel = `support-thread.${threadId}`;


        console.log('Subscribing:', this.currentChannel);

        window.Echo.channel(this.currentChannel)
            .listen('SupportMessageSent', e => {
                console.log('Realtime event:', e);

                this.appendMessage({
                    id: e.message.id,
                    sender_id: e.user.id,
                    message: e.message.message,
                    attachment: e.message.attachment,
                    created_at: e.message.created_at,
                });
            });
    }

    leaveChannel() {
        if (!this.currentChannel) return;

        console.log('Leaving:', this.currentChannel);
        window.Echo.leave(this.currentChannel);

        this.currentChannel = null;
        this.activeThreadId = null;
    }

    /* -------------------- MESSAGES -------------------- */

    async loadMessages() {
        if (!this.activeThreadId) return;

        const res = await fetch(`/api/support/threads/${this.activeThreadId}`, {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + localStorage.getItem('sanctum_token'),
            },
        });

        const messages = await res.json();

        messages.forEach(msg => {
            if (this.loadedMessageIds.has(msg.id)) return;
            this.loadedMessageIds.add(msg.id);
            this.appendMessage(msg);
        });

        this.scrollToBottom();
    }

    appendMessage(msg) {
        const isMine = msg.sender_id === this.AUTH_USER_ID;

        const div = document.createElement('div');
        div.className = `support_chat_message ${isMine ? 'support_chat_message_user' : 'support_chat_message_agent'
            }`;

        div.innerHTML = `
            ${msg.message ? `<p>${msg.message}</p>` : ''}
            ${msg.attachment
                ? `<img src="${msg.attachment}" style="max-width:150px">`
                : ''
            }
            <small>${isMine ? 'You' : 'Support'} · ${new Date(
                msg.created_at
            ).toLocaleTimeString()}</small>
        `;

        this.ui.messages.appendChild(div);
        this.scrollToBottom();
    }

    clearMessages() {
        this.loadedMessageIds.clear();
        this.ui.messages.innerHTML = '';
    }

    scrollToBottom() {
        this.ui.messages.scrollTop = this.ui.messages.scrollHeight;
    }

    /* -------------------- SEND -------------------- */


    async sendMessage() {
        if (!this.activeThreadId) return;

        const text = this.ui.textarea.value.trim();
        if (!text) return;

        try {
            const res = await fetch(`/api/support/reply/${this.activeThreadId}`, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    Authorization: 'Bearer ' + localStorage.getItem('sanctum_token'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: text }),
            });

            const data = await res.json();
            if (res.ok && data.success) {
                // Append only after backend confirms
                this.appendMessage({
                    id: data.data.id,
                    sender_id: this.AUTH_USER_ID,
                    message: data.data.message,
                    attachment: data.data.attachment || null,
                    created_at: data.data.created_at,
                });

                this.ui.textarea.value = '';
            } else {
                alert('Failed to send message.');
            }
        } catch (err) {
            console.error(err);
            alert('Error sending message.');
        }
    }

}

/* -------------------- BOOT -------------------- */

document.addEventListener('DOMContentLoaded', async () => {
    const chat = new ChatService();
    await chat.init();
});
