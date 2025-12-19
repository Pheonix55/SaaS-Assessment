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
                            <div class="field">
                                <label class="label_field">Email Address</label>
                                <input type="email" name="email" placeholder="E-mail" required />
                            </div>
                            <div class="field">
                                <label class="label_field">Password</label>
                                <input type="password" name="password" placeholder="Password" required />
                            </div>
                            <div class="field">
                                <label class="label_field hidden">hidden label</label>
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
            //  Loader.show();

            const formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ url('api/login') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: formData,
                    credentials: "include"
                })
                .then(response => response.json())
                .then(data => {
                    Loader.hide();

                    if (data.success) {
                        localStorage.setItem('sanctum_token', data.data.token);
                        window.location.href = "{{ url('/dashboard') }}";
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
