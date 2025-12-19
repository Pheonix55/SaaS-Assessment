<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- site metas -->
    <title>@yield('title', 'SaaS App')</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- site icon -->

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />

    <!-- site css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <!-- responsive css -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />

    <!-- color css -->
    <link rel="stylesheet" href="{{ asset('css/color_2.css') }}" />

    <!-- select bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}" />

    <!-- scrollbar css -->
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}" />

    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
</head>

<body class="dashboard dashboard_1">
    <div class="full_container">
        <div class="inner_container">
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar_blog_1">
                    <div class="sidebar-header">
                        <div class="logo_section">
                            <a href="index.html"><img class="logo_icon img-responsive" src="images/logo/logo_icon.png"
                                    alt="#" /></a>
                        </div>
                    </div>
                    <div class="sidebar_user_info">
                        <div class="icon_setting"></div>
                        <div class="user_profle_side">
                            <div class="user_img"><img class="img-responsive" src="images/layout_img/user_img.jpg"
                                    alt="#" /></div>
                            <div class="user_info">
                                <h6>John David</h6>
                                <p><span class="online_animation"></span> Online</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar_blog_2">
                    <h4>General</h4>
                    <ul class="list-unstyled components">

                        <li><a href="{{ route('dashboard') }}"><i
                                    class="fa fa-dashboard yellow_color"></i><span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="#element" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                                    class="fa fa-diamond purple_color"></i> <span>Susbscriptions</span></a>
                            <ul class="collapse list-unstyled" id="element">
                                <li><a href="{{ route('subscriptions') }}">> <span>PLans</span></a></li>
                                <li><a href="">>
                                        <span>my Subscriptions</span></a></li>
                                <li><a href="#">> <span>Invoice</span></a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('user.profile') }}"><i class="fa fa-cog yellow_color"></i>
                                <span>profile</span></a>
                        </li>
                        {{-- <li class="active">
                            <a href="#additional_page" data-toggle="collapse" aria-expanded="false"
                                class="dropdown-toggle"><i class="fa fa-clone yellow_color"></i> <span>Additional
                                    Pages</span></a>
                            <ul class="collapse list-unstyled" id="additional_page">
                                <li>
                                    <a href="profile.html">> <span>Profile</span></a>
                                </li>
                                <li>
                                    <a href="project.html">> <span>Projects</span></a>
                                </li>
                                <li>
                                    <a href="login.html">> <span>Login</span></a>
                                </li>
                                <li>
                                    <a href="404_error.html">> <span>404 Error</span></a>
                                </li>
                            </ul>
                        </li> --}}


                        <li><a href="#"><i class="fa fa-cog yellow_color"></i> <span>Settings</span></a>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- end sidebar -->
            <!-- right content -->
            <div id="content">
                <!-- topbar -->
                <div class="topbar">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="full">
                            <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i
                                    class="fa fa-bars"></i></button>
                            <div class="logo_section">
                                <a href="index.html"><img class="img-responsive" src="images/logo/logo.png"
                                        alt="#" /></a>
                            </div>
                            <div class="right_topbar">
                                <div class="icon_info">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-bell-o"></i><span
                                                    class="badge">2</span></a></li>
                                        <li><a href="#"><i class="fa fa-question-circle"></i></a></li>
                                        <li><a href="#"><i class="fa fa-envelope-o"></i><span
                                                    class="badge">3</span></a></li>
                                    </ul>
                                    <ul class="user_profile_dd">
                                        <li>
                                            <a class="dropdown-toggle" data-toggle="dropdown"><img
                                                    class="img-responsive rounded-circle"
                                                    src="images/layout_img/user_img.jpg" alt="#" /><span
                                                    class="name_user">John David</span></a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="profile.html">My Profile</a>
                                                <a class="dropdown-item" href="settings.html">Settings</a>
                                                <a class="dropdown-item" href="help.html">Help</a>
                                                <a class="dropdown-item" id="logout" href="#">
                                                    <span>Log Out</span> <i class="fa fa-sign-out"></i>
                                                </a>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
                @yield('content')
                <!-- end topbar -->
                <!-- dashboard inner -->

                <!-- end dashboard inner -->
            </div>
        </div>
    </div>
    {{-- support chart starts --}}
    <style>
        #chat-menu {
            position: fixed;
            bottom: 0;
            right: 0;
            z-index: 1050;
            display: flex;
            gap: 10px;
            border-top-left-radius: 10px;
            min-height: 60px;
            min-width: 200px;
            justify-content: space-evenly;
        }

        #chat-toggle,
        #chat-new-thread {
            /* width: 56px;
            height: 56px; */
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #chat-window {
            position: fixed;
            bottom: 0;
            right: 10px;
            width: 360px;
            height: 520px;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            overflow: hidden;
            z-index: 100000;
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

        .text-black {
            color: black !important;
        }
    </style>

    <div id="chat-menu"
        class="position-fixed bottom-0 start-50 translate-middle-x 
            d-flex align-items-center gap-3 px-3 py-2
            bg-white shadow rounded-pill border">

        <button id="chat-new-thread" class="btn bg-transparent  ">
            <i class="bi bi-plus text-black"></i>
        </button>

        <button id="chat-toggle" class="btn bg-transparent  ">
            <i class="bi bi-chat-dots text-black"></i>
        </button>

    </div>


    <!-- CHAT WINDOW -->
    <div id="chat-window" class="card shadow d-none">

        <div class="card-header d-flex justify-content-between align-items-center blue1_bg text-white">
            <span>Support</span>
            <button id="chat-close" class="btn bg-transparent text-white">X</button>
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
    {{-- support chat ends --}}
    @include('partials.loader')

    @yield('scripts')


    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- wow animation -->
    <script src="{{ asset('js/animate.js') }}"></script>

    <!-- select country -->
    <script src="{{ asset('js/bootstrap-select.js') }}"></script>

    <!-- owl carousel -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/owl.carousel.js') }}"></script>

    <!-- chart js -->
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/analyser.js') }}"></script>

    <!-- nice scrollbar -->
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
    <script>
        const ps = new PerfectScrollbar('#sidebar');
    </script>

    <!-- custom js -->
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/chart_custom_style1.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            fetch('/api/me', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Unauthorized');
                    return res.json();
                })
                .then(user => {
                    console.log('Authenticated user:', user);

                    // later:
                    // document.getElementById('user-name').textContent = user.name;
                    // window.currentUser = user;
                })
                .catch(err => {
                    console.error('Auth user fetch failed:', err);
                });



            const logoutLink = document.querySelector('#logout');

            if (!logoutLink) return;

            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();

                const token = localStorage.getItem('sanctum_token');

                fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Logout failed');
                        localStorage.removeItem('sanctum_token');
                        window.location.href = '/';
                    })
                    .catch(err => console.error(err));
            });

        });
    </script>
    {{-- supoprt script --}}
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

            // --- FETCH THREADS ---
            // function fetchThreads() {
            //     fetch('/api/support/threads', {
            //             headers: {
            //                 'Accept': 'application/json',
            //                 'Authorization': 'Bearer ' + token
            //             }
            //         })
            //         .then(res => res.json())
            //         .then(data => {
            //             threadList.innerHTML = '';
            //             chatView.classList.add('d-none');
            //             lastMessageTime = null; // reset for new thread

            //             data.forEach(thread => {
            //                 const div = document.createElement('div');
            //                 div.className = 'thread-item';
            //                 div.innerHTML = `
        //         <div class="thread-title">${thread.title}</div>
        //         <div class="thread-sub">Tap to open conversation</div>
        //     `;
            //                 div.onclick = () => openThread(thread.id, div);
            //                 threadList.appendChild(div);
            //             });
            //         });
            // }

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


            //     function loadMessages() {
            //         if (!activeThread) return;

            //         let url = `/api/support/threads/${activeThread}`;
            //         if (lastMessageTime) {
            //             url += `?after=${lastMessageTime}`;
            //         }

            //         fetch(url, {
            //                 headers: {
            //                     'Accept': 'application/json',
            //                     'Authorization': 'Bearer ' + token
            //                 }
            //             })
            //             .then(res => res.json())
            //             .then(data => {
            //                 if (!data.length) return;

            //                 data.forEach(msg => appendMessage(msg));

            //                 lastMessageTime = data[data.length - 1].created_at;

            //                 chatMessages.scrollTop = chatMessages.scrollHeight;
            //             });
            //     }

            //     // --- APPEND MESSAGE ---
            //     function appendMessage(msg) {
            //         const row = document.createElement('div');
            //         row.className = 'message-row ' + (msg.sender_type === 'user' ? 'user' : 'support');

            //         const bubble = document.createElement('div');
            //         bubble.className = 'message ' + (msg.sender_type === 'user' ? 'user' : 'support');

            //         const time = new Date(msg.created_at).toLocaleTimeString([], {
            //             hour: '2-digit',
            //             minute: '2-digit'
            //         });

            //         bubble.innerHTML = `
        //     <div>${msg.message}</div>
        //     <div class="message-meta">
        //         <span>${time}</span>
        //         ${msg.sender_type === 'user' ? '<span class="tick">✓✓</span>' : ''}
        //     </div>
        // `;

            //         row.appendChild(bubble);
            //         chatMessages.appendChild(row);
            //     }

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


            // --- SEND MESSAGE ---
            chatSend.onclick = () => {
                const message = chatInput.value.trim();
                if (!message || !activeThread) return;

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
                        // loadMessages(); // reload after sending
                    });
            };

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.href;
            const sidebarLinks = document.querySelectorAll('.components a');

            sidebarLinks.forEach(link => {
                link.parentElement.classList.remove('active');

                if (link.href === currentUrl) {
                    link.parentElement.classList.add('active');

                    const parentUl = link.closest('ul.collapse');
                    if (parentUl) {
                        parentUl.classList.add('show');
                        const parentLi = parentUl.closest('li');
                        if (parentLi) {
                            parentLi.classList.add('active');
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
