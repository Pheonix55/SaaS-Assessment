@extends('layouts.auth')
@section('content')
    <div class="card" style="width: 40%;">
        <div class="card-body">
            <h5 class="card-title">Login</h5>
            <form class="row g-3" id="registerform">
                @csrf
                <div class="col-md-12">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>


                
                <div class="col-12 d-flex justify-content-between" >
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a class="" href="{{ route('register') }}"> SignUp</a>
                </div>
            </form>

        </div>
    </div>
    <script>
        document.getElementById('registerform').addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch("{{ url('api/login') }}", {
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
                        window.location.href = "{{ url('/dashboard') }}";
                    } else {
                        alert('Error occurred');
                    }
                })
                .catch(error => console.error(error));
        });
    </script>

@endsection