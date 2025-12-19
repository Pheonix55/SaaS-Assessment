@extends('layouts.auth')
@section('content')
    <div class="card" style="width: 60%;">
        <div class="card-body">
            <h5 class="card-title">Register</h5>
            <form class="row g-3" id="registerform">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">addess</label>
                    <input type="text" class="form-control" name="address" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">phone</label>
                    <input type="text" class="form-control" name="phone" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">company site</label>
                    <input type="text" class="form-control" name="website" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>

        </div>
    </div>
    <script>
        document.getElementById('registerform').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            Loader.show();
            fetch("{{ url('api/register') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration successful');
                        window.location.href = "{{ url('/') }}";
                        Loader.hide();
                    } else {
                        alert('Error occurred');
                    }
                })
                .catch(error => console.error(error));
        });
    </script>
@endsection
