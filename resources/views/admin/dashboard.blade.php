@extends('layouts.app')
@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1>Admin Dashboard</h1>
                <p>Welcome to the admin dashboard!</p>
            </div>

            <div class="subscription-info">
                <h2>Subscription Details</h2>
                <p><strong>Plan:</strong> {{ $subscription->plan_name ?? '--' }}</p>
                <p><strong>Status:</strong> {{ $subscription->status ?? 'expired' }}</p>
                <p><strong>Next Billing Date:</strong> {{ $subscription->next_billing_date ?? '--' }}</p>
            </div>
              
    </div>


    <script>
        document.getElementById('registerform').addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch("{{ url('api/getSubscriptionInfo') }}", {
                method: "GET",
                headers: {
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
