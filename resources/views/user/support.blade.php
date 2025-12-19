@extends('layouts.app')
@section('content')
    <style>
        #chat-menu {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
            display: flex;
            gap: 10px;
        }

        #chat-toggle,
        #chat-new-thread {
            width: 56px;
            height: 56px;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #chat-window {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 360px;
            height: 520px;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            overflow: hidden;
        }

        /* THREAD LIST (WhatsApp style) */
        #thread-list {
            flex: 1;
            overflow-y: auto;
            background: #f0f2f5;
        }

        .thread-item {
            padding: 12px 14px;
            cursor: pointer;
            border-bottom: 1px solid #e0e0e0;
            background: #fff;
        }

        .thread-item:hover {
            background: #f5f6f6;
        }

        .thread-item.active {
            background: #e9edef;
        }

        .thread-title {
            font-weight: 600;
            font-size: 14px;
        }

        .thread-sub {
            font-size: 12px;
            color: #6c757d;
        }

        #chat-view {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #efeae2;
        }

        #chat-messages {
            flex: 1;
            padding: 12px;
            overflow-y: auto;
            max-height: 80%;
        }

        .message {
            max-width: 75%;
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message.user {
            margin-left: auto;
            background: #d9fdd3;
        }

        .message.support {
            background: #ffffff;
        }

        .chat-input-area {
            padding: 8px;
            background: #f0f2f5;
            display: flex;
            gap: 8px;
        }

        /* MESSAGE ROW (controls left/right alignment) */
        .message-row {
            display: flex;
            margin-bottom: 6px;
        }

        .message-row.user {
            justify-content: flex-end;
        }

        .message-row.support {
            justify-content: flex-start;
        }

        /* MESSAGE BUBBLE */
        .message {
            max-width: 70%;
            padding: 8px 10px 6px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.4;
            position: relative;
        }

        .message.user {
            background: #d9fdd3;
            border-top-right-radius: 0;
        }

        .message.support {
            background: #ffffff;
            border-top-left-radius: 0;
            width: 100%;
        }

        /* META (time + ticks) */
        .message-meta {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: #667781;
            margin-top: 2px;
        }

        .message.support .message-meta {
            justify-content: flex-end;
        }

        /* Tick icons */
        .tick {
            font-size: 12px;
        }
    </style>

    <div id="chat-menu">
        <button id="chat-new-thread" class="btn btn-success rounded-circle">
            <i class="bi bi-plus"></i>
        </button>

        <button id="chat-toggle" class="btn btn-primary rounded-circle">
            <i class="bi bi-chat-dots"></i>
        </button>
    </div>

    <!-- CHAT WINDOW -->
    <div id="chat-window" class="card shadow d-none">

        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <span>Support</span>
            <button id="chat-close" class="btn btn-sm btn-light">&times;</button>
        </div>

        <!-- THREAD LIST -->
        <div id="thread-list"></div>

        <!-- CHAT VIEW -->
        <div id="chat-view" class="d-none">
            <div id="chat-messages"></div>

            <div class="chat-input-area">
                <input type="text" id="chat-input" class="form-control">
                <button id="chat-send" class="btn btn-success">
                    <i class="bi bi-send"></i>
                </button>
            </div>
        </div>
    </div>



    <div class="modal fade" id="newThreadModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Create Support Ticket</h5>
                </div>
                <div class="modal-body">
                    <input id="thread-title" class="form-control mb-2" placeholder="Title">
                    <textarea id="thread-desc" class="form-control" placeholder="Description"></textarea>
                </div>
                <div class="modal-footer">
                    <button id="create-thread-btn" class="btn btn-success">Create</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- AUTH USER DATA ----
        let AUTH_USER_ID = null;
        const token = localStorage.getItem('sanctum_token');

        async function fetchAuthUser() {
            const res = await fetch('/api/me', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
            });

            if (!res.ok) {
                console.error('Failed to fetch auth user');
                return;
            }

            const data = await res.json();
            AUTH_USER_ID = data.id;
            AUTH_USER_NAME = data.name;
            AUTH_COMPANY_ID = data.company_id;
        }

        document.addEventListener('DOMContentLoaded', function() {
            initChat();
        });

        async function initChat() {
            await fetchAuthUser();

            if (!AUTH_USER_ID) {
                console.error('Auth user not loaded');
                return;
            }
            const chatToggle = document.getElementById('chat-toggle');
            const chatNew = document.getElementById('chat-new-thread');
            const chatWindow = document.getElementById('chat-window');
            const chatClose = document.getElementById('chat-close');

            const threadList = document.getElementById('thread-list');
            const chatView = document.getElementById('chat-view');
            const chatMessages = document.getElementById('chat-messages');
            const chatInput = document.getElementById('chat-input');
            const chatSend = document.getElementById('chat-send');
            const loadedMessageIds = new Set();



            const modal = new bootstrap.Modal(document.getElementById('newThreadModal'));
            let lastMessageId = null;

            let activeThread = null;
            let lastMessageTime = null; // track last message timestamp for polling
            let isChatOpen = false;


            await fetchAuthUser();

            if (!AUTH_USER_ID) {
                console.error('Auth user not loaded');
                return;
            }

            // --- CHAT TOGGLE ---
            chatToggle.onclick = () => {
                chatWindow.classList.toggle('d-none');
                isChatOpen = !chatWindow.classList.contains('d-none');

                if (isChatOpen) fetchThreads();
                else stopPolling();
            };


            chatClose.onclick = () => chatWindow.classList.add('d-none');
            chatNew.onclick = () => modal.show();

            // --- CREATE NEW THREAD ---
            document.getElementById('create-thread-btn').onclick = () => {
                const title = document.getElementById('thread-title').value;
                const description = document.getElementById('thread-desc').value;

                fetch('/api/support/threads', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            title,
                            description
                        })
                    })
                    .then(res => res.json())
                    .then(() => {
                        modal.hide();
                        fetchThreads();
                    });
            };



            function fetchThreads() {
                fetch('/api/support/threads', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        threadList.innerHTML = '';
                        chatView.classList.add('d-none');
                        lastMessageId = null;

                        data.forEach(thread => {
                            const div = document.createElement('div');
                            div.className =
                                'thread-item d-flex justify-content-between align-items-center';
                            div.innerHTML = `
                <div class="thread-info" style="flex:1; cursor:pointer;">
                    <div class="thread-title">${thread.title}</div>
                    <div class="thread-sub">${thread.status === 'closed' ? 'Closed' : 'Open'}</div>
                </div>
                ${thread.status !== 'closed' ? `<button class="btn btn-sm btn-danger close-thread-btn">Close</button>` : ''}
            `;

                            div.querySelector('.thread-info').onclick = () => openThread(thread.id,
                                div);

                            const closeBtn = div.querySelector('.close-thread-btn');
                            if (closeBtn) {
                                closeBtn.onclick = (e) => {
                                    e.stopPropagation(); // prevent opening thread
                                    closeThread(thread.id, div);
                                }
                            }

                            threadList.appendChild(div);
                        });
                    });
            }
            // --- CLOSE THREAD ---

            function closeThread(threadId, threadDiv) {
                fetch(`/api/support/threads/${threadId}/close`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        // Update UI
                        const sub = threadDiv.querySelector('.thread-sub');
                        sub.textContent = 'Closed';
                        const btn = threadDiv.querySelector('.close-thread-btn');
                        if (btn) btn.remove();
                    });
            }


            // --- OPEN THREAD ---
            function openThread(threadId) {
                activeThread = threadId;
                chatView.classList.remove('d-none');
                loadedMessageIds.clear();
                lastMessageId = null;
                chatMessages.innerHTML = '';
                lastMessageTime = null;

                loadMessages();
                startPolling(); // start auto-update
            }

            // --- LOAD MESSAGES ---



            function appendMessage(msg) {
                const isMine = msg.sender_id === AUTH_USER_ID;

                const row = document.createElement('div');
                row.className = 'message-row ' + (isMine ? 'user' : 'support');

                const bubble = document.createElement('div');
                bubble.className = 'message ' + (isMine ? 'user' : 'support');

                const time = new Date(msg.created_at).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                bubble.innerHTML = `
        <div>${msg.message}</div>
        <div class="message-meta">
            <span>${time}</span>
            ${isMine ? '<span class="tick">✓✓</span>' : ''}
        </div>
    `;

                row.appendChild(bubble);
                chatMessages.appendChild(row);
            }



            function loadMessages() {
                if (!activeThread) return;

                let url = `/api/support/threads/${activeThread}`;
                if (lastMessageId) {
                    url += `?after_id=${lastMessageId}`;
                }

                fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.length) return;

                        data.forEach(msg => {
                            if (loadedMessageIds.has(msg.id)) return;

                            loadedMessageIds.add(msg.id);
                            appendMessage(msg);
                        });

                        lastMessageId = data[data.length - 1].id;
                        scrollIfNearBottom();
                    });

            }
            // chatInput.addEventListener('keydown', function(e) {
            //     if (e.key === 'Enter' && !e.shiftKey) {
            //         e.preventDefault();
            //         console.log(e.key);
            //         const message = chatInput.value.trim();
            //         if (!message) return;
            //         sendMessage();
            //     }
            // });

            // --- SEND MESSAGE ---
            let canSend = true; 
            const SEND_COOLDOWN = 2000; 

            function sendMessage(message) {
                if (!activeThread || !canSend) return;

                canSend = false; 
                setTimeout(() => canSend = true, SEND_COOLDOWN); 

                fetch(`/api/support/reply/${activeThread}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message
                        })
                    })
                    .then(res => res.json())
                    .then(() => {
                        chatInput.value = '';

                        // append immediately for smooth UX
                        appendMessage({
                            id: 'temp_' + Date.now(),
                            message: message,
                            sender_id: AUTH_USER_ID,
                            created_at: new Date().toISOString()
                        });
                        scrollIfNearBottom();
                    });
            }


            chatSend.onclick = sendMessage;

            // --- POLLING (auto-update) ---
            let pollingInterval = null;

            function startPolling() {
                stopPolling();

                let interval = 2000;

                pollingInterval = setInterval(() => {
                    if (!activeThread || !isChatOpen) return;

                    loadMessages();

                    // Backoff after inactivity
                    interval = lastMessageId ? 5000 : 2000;
                }, interval);
            }


            function stopPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            function scrollIfNearBottom() {
                const threshold = 100;
                const isNearBottom =
                    chatMessages.scrollHeight -
                    chatMessages.scrollTop -
                    chatMessages.clientHeight < threshold;

                if (isNearBottom) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }



        };
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {

            const chatToggle = document.getElementById('chat-toggle');
            const chatNew = document.getElementById('chat-new-thread');
            const chatWindow = document.getElementById('chat-window');
            const chatClose = document.getElementById('chat-close');

            const threadList = document.getElementById('thread-list');
            const chatView = document.getElementById('chat-view');
            const chatMessages = document.getElementById('chat-messages');
            const chatInput = document.getElementById('chat-input');
            const chatSend = document.getElementById('chat-send');

            const modal = new bootstrap.Modal(document.getElementById('newThreadModal'));
            let lastMessageTime = null;



            let activeThread = null;
            const token = localStorage.getItem('sanctum_token');

            chatToggle.onclick = () => {
                chatWindow.classList.toggle('d-none');
                fetchThreads();
            };

            chatClose.onclick = () => chatWindow.classList.add('d-none');

            chatNew.onclick = () => modal.show();

            document.getElementById('create-thread-btn').onclick = () => {
                fetch('/api/support/threads', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            title: document.getElementById('thread-title').value,
                            description: document.getElementById('thread-desc').value
                        })
                    })
                    .then(res => res.json())
                    .then(() => {
                        modal.hide();
                        fetchThreads();
                    });
            };

            function fetchThreads() {
                fetch('/api/support/threads', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        threadList.innerHTML = '';
                        chatView.classList.add('d-none');

                        data.forEach(thread => {
                            const div = document.createElement('div');
                            div.className = 'thread-item';
                            div.innerHTML = `
                                <div class="thread-title">${thread.title}</div>
                                <div class="thread-sub">Tap to open conversation</div>
                            `;
                            div.onclick = () => openThread(thread.id, div);
                            threadList.appendChild(div);
                        });
                    });
            }

            function openThread(threadId) {
                activeThread = threadId;
                chatView.classList.remove('d-none');
                loadMessages();
            }


            function loadMessages() {
                fetch(`/api/support/threads/${activeThread}`, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        chatMessages.innerHTML = '';

                        // data.forEach(msg => {
                        //     const div = document.createElement('div');
                        //     div.className = 'message ' + (msg.sender_type === 'user' ? 'user' :
                        //         'support');
                        //     div.textContent = msg.message;
                        //     chatMessages.appendChild(div);
                        // });
                        data.forEach(msg => {
                            const row = document.createElement('div');
                            row.className = 'message-row ' + (msg.sender_type === 'user' ? 'user' :
                                'support');

                            const bubble = document.createElement('div');
                            bubble.className = 'message ' + (msg.sender_type === 'user' ? 'user' :
                                'support');

                            const time = new Date(msg.created_at).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            bubble.innerHTML = `
        <div>${msg.message}</div>
        <div class="message-meta">
            <span>${time}</span>
            ${msg.sender_type === 'user' ? '<span class="tick">✓✓</span>' : ''}
        </div>
    `;

                            row.appendChild(bubble);
                            chatMessages.appendChild(row);
                        });


                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    });
            }


            chatSend.onclick = () => {
                fetch(`/api/support/reply/${activeThread}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message: chatInput.value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        chatInput.value = '';
                        loadMessages();
                    });
            };
        });
    </script> --}}
@endsection
