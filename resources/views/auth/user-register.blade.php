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
                    <form id="registerForm" data-token="{{ $token }}">
                        <fieldset class="">

                            <div class="field ">
                                <label class="label_field">Name</label>
                                <input type="text" name="name" placeholder="Full Name" required />
                            </div>
                            <div class="field d-flex">
                                <label class="label_field" for="email">Email</label>
                                <div class="auth-input-group no-border d-flex justify-content-between align-items-center">
                                    <input type="email" name="email" id="email" placeholder="E-mail"
                                        value="{{ $email ?? '' }}" required autocomplete="email"
                                        @if ($email) readonly @endif />
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

            const formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // const urlParams = new URLSearchParams(window.location.search);
            const invitationToken = this.dataset.token;
            // const invitationToken = urlParams.get('token');

            let registerUrl = "{{ url('/api/invite-accept') }}" +'/' +invitationToken;

            // if (invitationToken) {
            //     registerUrl += `?token=${encodeURIComponent(invitationToken)}`;
            // }

            fetch(registerUrl, {
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
                    // if (invitationToken) {

                    //     const acceptResponse = await fetch(
                    //         "{{ url('/api/invite-accept') }}/" + invitationToken, {
                    //             method: 'GET',
                    //             headers: {
                    //                 'Accept': 'application/json',
                    //                 'X-CSRF-TOKEN': token,
                    //                 'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                    //             }
                    //         }
                    //     );

                    //     const acceptData = await acceptResponse.json();

                    //     if (!acceptResponse.ok) {
                    //         alert(acceptData.message || 'Failed to accept invitation');
                    //         return;
                    //     }

                    //     alert(acceptData.message || 'Invitation accepted successfully');

                    //     window.location.href = "{{ url('/') }}";
                    //     return;
                    // }

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
