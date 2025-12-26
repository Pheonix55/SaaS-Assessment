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
    @vite(['resources/js/app.js', 'resources/css/app.css'])



</head>

<body class="dashboard dashboard_1">
    <div class="full_container">
        <div class="inner_container">
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar_blog_1">
                    <div class="sidebar-header">
                        <div class="logo_section">
                            <a href="index.html"><img class="logo_icon img-responsive" src="../images/logo/logo.png"
                                    alt="#" /></a>
                        </div>
                    </div>
                    <div class="sidebar_user_info">
                        <div class="icon_setting"></div>
                        <div class="user_profle_side">
                            <div class="user_img"><img
                                    class="img-responsive img-fluid rounded-circle user_img_yxwz top_navbar_img"
                                    src="images/layout_img/user_img.jpg" alt="#" /></div>
                            <div class="user_info">
                                <h6 class="user_name_ytxf">John David</h6>
                                <p><span class="online_animation"></span> Online</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar_blog_2">
                    <h4>General</h4>
                    <ul class="list-unstyled components">

                        <li><a href="{{ route('admin.dashboard') }}"><i
                                    class="fa fa-dashboard yellow_color"></i><span>Dashboard</span></a>
                        </li>

                        <li><a href="{{ route('user.profile') }}"><i class="fa fa-cog yellow_color"></i>
                                <span>profile</span></a>
                        </li>


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
                                <a href="index.html"><img class="img-responsive" src="../images/logo/logo.png"
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
                                            <a class="dropdown-toggle d-flex" data-toggle="dropdown"><img
                                                    class="img-responsive img-fluid rounded-circle user_img_yxwz"
                                                    src="images/layout_img/user_img.jpg" alt="#" /><span
                                                    class="user_name_ytxf">John David</span></a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('user.profile') }}">My
                                                    Profile</a>
                                                <a class="dropdown-item" href="#">Settings</a>
                                                {{-- <a class="dropdown-item" href="help.html">Help</a> --}}
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

    <!-- SUPPORT CHAT TOGGLE BUTTON -->
    <div id="chat-menu" class="position-fixed bottom-0 end-0 d-flex gap-2 p-2 bg-white shadow rounded-pill">
        <button id="chat-new-thread" class="btn btn-primary">New Ticket</button>
        <button id="chat-toggle" class="btn btn-success">Chat</button>
    </div>

    <!-- SUPPORT CHAT MODAL -->
    <div class="support_chat_modal_overlay support_chat_hidden" id="supportChatModal">

        <div class="support_chat_modal">

            <!-- Close -->
            <button class="support_chat_modal_close">✕</button>

            <div class="support_chat_app">

                <!-- Sidebar -->
                <aside class="support_chat_sidebar d-flex flex-column">
                    <div class="mb-3">
                        <h2>Recent Tickets</h2>
                        <input type="text" placeholder="Search..." class="support_chat_search">
                    </div>
                    <!-- Tickets will be injected dynamically by JS -->
                </aside>

                <!-- Main Content -->
                <main class="support_chat_content">
                    <header class="support_chat_header">
                        <h3 id="chat-thread-title">Select a ticket</h3>
                        <div class="support_chat_tags" id="chat-thread-tags">
                            <!-- Tags injected dynamically -->
                        </div>
                    </header>

                    <section class="support_chat_messages">
                        <!-- Messages appended dynamically -->
                    </section>

                    <footer class="support_chat_reply_box">
                        <textarea class="support_chat_textarea" placeholder="Type your reply..."></textarea>
                        <button class="support_chat_send_btn">Send</button>
                        <label for="chat-image" id="chat-image-btn" class="btn btn-outline-secondary mb-0">
                            <i class="bi bi-image"></i>
                        </label>
                        <input type="file" id="chat-image" accept="image/*" style="display:none">
                    </footer>
                </main>
            </div>
        </div>
    </div>

    <!-- NEW THREAD MODAL -->
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

    <!-- ATTACHMENT MODAL -->
    <div class="modal fade z_index" id="attachmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Attachment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="file" id="modal-attachment" accept="image/*" class="form-control mb-2">
                    <div id="modal-preview" class="mb-2"></div>
                    <textarea id="modal-message" class="form-control" rows="2" placeholder="Type a message..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-send-btn" class="btn btn-success">Send</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .z_index {
            z-index: 99999;
        }

        .support_chat_hidden {
            display: none !important;
        }

        .support_chat_modal_overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .support_chat_modal {
            background: #fff;
            width: 90%;
            max-width: 1200px;
            height: 85vh;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .support_chat_modal_close {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 18px;
            cursor: pointer;
            border: none;
            background: none;
            z-index: 10;
        }

        .support_chat_app {
            display: flex;
            height: 100%;
            background: #f5f7fb;
        }

        .support_chat_sidebar {
            width: 300px;
            background: #fff;
            border-right: 1px solid #ddd;
            padding: 15px;
            overflow-y: auto;
        }

        .support_chat_ticket {
            display: flex;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 5px;
        }

        .support_chat_ticket_active {
            background: #eef3ff;
        }

        .support_chat_avatar {
            background: #4f6ef7;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
        }

        .support_chat_content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .support_chat_header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            background: #fff;
        }

        .support_chat_messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .support_chat_message {
            max-width: 60%;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .support_chat_message_user {
            background: #e8f0ff;
        }

        .support_chat_message_agent {
            background: #dff5ea;
            margin-left: auto;
        }

        .support_chat_reply_box {
            display: flex;
            padding: 15px;
            background: #fff;
            border-top: 1px solid #ddd;
            align-items: center;
            gap: 5px;
        }

        .support_chat_textarea {
            flex: 1;
            resize: none;
            padding: 10px;
        }

        .support_chat_send_btn {
            padding: 10px 20px;
            background: #4f6ef7;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .support_chat_search {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }

        .support_chat_tag {
            padding: 2px 6px;
            font-size: 11px;
            border-radius: 4px;
            margin-right: 5px;
        }

        .support_chat_open {
            background: #dff5ea;
            color: #0a7b4f;
        }

        .support_chat_high {
            background: #ffe0e0;
            color: #c0392b;
        }

        .support_chat_new {
            background: #e0ebff;
            color: #3456d1;
        }

        .support_chat_sales {
            background: #fff0d9;
            color: #a86400;
        }
    </style>




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
    <script src="{{ asset('js/secureFetch.js') }}"></script>

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


    <!-- custom js -->


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutLink = document.querySelector('#logout');

            if (!logoutLink) return;

            logoutLink.addEventListener('click', function(e) {
                Loader.show();
                e.preventDefault();

                const token = localStorage.getItem('sanctum_token');

                fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Logout failed');
                        localStorage.removeItem('sanctum_token');
                        Loader.hide();
                        window.location.href = '/';
                    })
                    .catch(err => console.error(err));
            });

        });
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });
        });
    </script>
    <script>
        Loader.show();
        window.addEventListener('load', function() {
            setTimeout(() => {
                Loader.hide();
            }, 4000);
        });
    </script>
</body>

</html>
