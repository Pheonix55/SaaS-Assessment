<!DOCTYPE html>
<html lang="en" data-sidebar-collapse="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SaaS App')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <style>
        html[data-sidebar-collapse="true"] .sidebar {
            width: 65px;
        }

        html[data-sidebar-collapse="true"] .nav-link p,
        html[data-sidebar-collapse="true"] .top-nav-link p {
            display: none;
        }

        body {
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #fff;
        }

        .nav-link {
            padding: unset !important;
            padding-top: 1rem !important;
            padding-left: 1rem !important;
        }

        .sidebar .nav-link {
            color: #343a40;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #eee;
            background-color: #343a40;
        }

        .content-wrapper {
            flex: 1;
            padding: 24px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light px-3">
        <a class="navbar-brand fw-bold" href="#">SaaS App</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
            <span class="navbar-toggler-icon">q</span>
        </button>

        <div class="collapse navbar-collapse" id="topNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="nav-link text-muted small">Tenant Dashboard</span>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-3">
                    <i class="bi bi-bell text-white"></i>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex justify-content-center align-items-baseline gap-1"
                        href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                        <span id="user-name"> Account</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Company Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" id="logout-link">Logout</a>
                        </li>


                    </ul>
                </li>
            </ul>
        </div>
    </nav>


    <!-- Layout Wrapper -->
    <div class="d-flex">

        <!-- Sidebar -->
        <aside class="sidebar p-3">
            <div class="mb-4  fw-bold top-nav-link d-flex gap-1">
                <i class="bi bi-building"></i>
                <p>My Company</p>
            </div>

            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item">
                    <a class="nav-link d-flex active" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex" href="#">
                        <i class="bi bi-people me-2"></i>
                        <p>Team Members</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex" href="#">
                        <i class="bi bi-credit-card me-2"></i>
                        <p>Billing</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex" href="{{ route('support.chat') }}">
                        <i class="bi bi-chat-dots me-2"></i>
                        <p>Support</p>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <a class="nav-link d-flex" href="#">
                        <i class="bi bi-gear me-2"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content-wrapper">
            @yield('content')
        </main>

    </div>

    <!-- Loader -->
    @include('partials.loader')

    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameEl = document.getElementById('user-name');

            fetch('/api/me', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res.name);
                    usernameEl.textContent = res.name;
                });
        });
    </script>
    <script>
        document.getElementById('logout-link').addEventListener('click', function(e) {
            e.preventDefault();

            const sanctum_token = localStorage.getItem('sanctum_token');
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

            fetch("{{ url('/api/logout') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + sanctum_token
                    },
                    credentials: 'same-origin'
                })
                .then(res => {
                    if (res.ok) {
                        localStorage.removeItem('sanctum_token');
                        window.location.href = "{{ url('/') }}";
                    } else {
                        return res.json().then(data => {
                            throw data;
                        });
                    }
                })
                .catch(err => console.error('Logout failed', err));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
