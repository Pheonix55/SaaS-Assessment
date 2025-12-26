@extends('layouts.auth')
@section('title', 'Register')

@section('content')
    <div class="container">
        <div class="center verticle_center h-100">
            <div class="login_section py-5">
                <div class="logo_login">
                    <div class="center">
                        <h3 class="text-white">Create Account</h3>
                    </div>
                </div>

                <div class="login_form">
                    <form id="registerForm">
                        <fieldset class="">

                            <div class="field ">
                                <label class="label_field">Name</label>
                                <input type="text" name="name" placeholder="Full Name" required />
                            </div>

                            <div class="field ">
                                <label class="label_field">Address</label>
                                <input type="text" name="address" placeholder="Address" required />
                            </div>

                            <div class="field ">
                                <label class="label_field">Phone</label>
                                <input type="text" name="phone" placeholder="Phone Number" required />
                            </div>

                            <div class="field ">
                                <label class="label_field">Company Website</label>
                                <input type="text" name="website" placeholder="Company Website" required />
                            </div>

                            <div class="field d-flex">
                                <label class="label_field" for="email">Email</label>
                                <div class="auth-input-group no-border d-flex justify-content-between align-items-center">
                                    <input type="email" name="email" id="email" placeholder="E-mail" required
                                        autocomplete="true" />
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <div class="field d-flex">
                                <label class="label_field">Password</label>
                                <div class="auth-input-group no-border d-flex justify-content-between align-items-center">
                                    <input type="password" name="password" id="password" placeholder="Password" required />
                                    <i class="fa-solid fa-eye toggle-password" style="cursor:pointer;"></i>
                                </div>
                            </div>

                            <div class="field ">
                                <label class="label_field">Confirm Password</label>

                                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                    required />
                            </div>

                            <div class="field margin_0 text-center mt-2">
                                <button type="submit" class="main_bt">Register</button>
                            </div>

                            <div class="field">
                                <a class="forgot" href="{{ route('login') }}">Already have an account? Login</a>
                            </div>

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Loader.show();
            const formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const urlParams = new URLSearchParams(window.location.search);
            const invitationToken = urlParams.get('token');

            fetch("{{ url('api/register') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(async data => {
                    Loader.hide();

                    if (!data.success) {
                        alert(data.message || 'Registration failed');
                        return;
                    }

                    alert('Registration successful');
                    window.location.href = "{{ url('/') }}";

                })
                .catch(err => {
                    Loader.hide();
                    console.error(err);
                    alert('An unexpected error occurred');
                });

            Loader.show();
        });
    </script>
@endsection

@endsection
