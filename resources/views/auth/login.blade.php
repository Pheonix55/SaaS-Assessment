@extends('layouts.auth')
@section('title', 'Authentication')

@section('content')
    <div class="container">
        <div class="center verticle_center full_height">
            <div class="login_section">
                <div class="logo_login">
                    <div class="center">
                        {{-- <img width="210" src="images/logo/logo.png" alt="#" /> --}}
                        <h3 class="text-white"> Welcome</h3>
                    </div>
                </div>
                <div class="login_form">
                    <form id="loginForm">
                        <fieldset>
                            <div class="field d-flex">
                                {{-- <i class="fa-solid fa-envelope"></i> --}}
                                <label class="label_field" for="email">Email</label>
                                <div class="auth-input-group d-flex justify-content-between align-items-center">
                                    <input type="email" name="email" id="email" placeholder="E-mail" required
                                        autocomplete="true" />
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <div class="field d-flex">
                                <label class="label_field">Password</label>

                                <div class="auth-input-group d-flex justify-content-between align-items-center">
                                    <input type="password" name="password" id="password" placeholder="Password" required />
                                    <i class="fa-solid fa-eye toggle-password" style="cursor:pointer;"></i>
                                </div>
                            </div>
                            <div class="">
                                <label class="label_field hidden">hidden label</label>
                            </div>
                            <div class="field justify-content-between w-100">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="remember"> Remember Me
                                </label>
                                <a class="forgot" href="{{ route('register') }}">New User? Register</a>
                            </div>
                            <div class="field margin_0">
                                <label class="label_field hidden">hidden label</label>
                                <button type="submit" class="main_bt">Sign In</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
             Loader.show();

            const formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const xsrf = decodeURIComponent(
                document.cookie
                .split('; ')
                .find(row => row.startsWith('XSRF-TOKEN='))
                .split('=')[1]
            );

            console.log(token, xsrf);
            fetch("{{ url('api/login') }}", {
                    method: "POST",
                    headers: {
                       'X-XSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: formData,
                    credentials: "same-origin"
                })
                .then(response => response.json())
                .then(data => {
                    Loader.hide();

                    if (data.success) {
                        localStorage.setItem('sanctum_token', data.data.token);
                        console.log(data.data);
                        window.location.href = data.data.redirect;
                    } else {
                        alert(data.message || 'Login failed.');
                    }
                })
                .catch(error => {
                    Loader.hide();
                    console.error(error);
                    alert('An unexpected error occurred.');
                });
        });
    </script>

@endsection
