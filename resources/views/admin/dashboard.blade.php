@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Admin Dashboard</h1>
                <p>Welcome to the admin dashboard!</p>
            </div>


            <div class="col-12 mb-4" id="subscription-info">
                <div class="subscription-info">
                    <h2>Current Subscription</h2>
                    <p><strong>Plan:</strong> --</p>
                    <p><strong>Status:</strong> --</p>
                    <p><strong>Next Billing Date:</strong> --</p>
                </div>
            </div>
        </div>

        <div class="row" id="plans-container">
        </div>
    </div>


    <!-- Bootstrap Modal -->
    <div class="modal fade" id="payment-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enter Card Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="card-element"></div>
                    <div id="card-errors" class="text-danger mt-2"></div>
                </div>

                <div class="modal-footer">
                    <button id="confirm-payment" class="btn btn-success">
                        Confirm Subscription
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ config('cashier.key') }}");
        const elements = stripe.elements();
        let cardElement = null;
        let selectedPlanId = null;
    </script>


    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('sanctum_token');
            const subscriptionInfo = document.getElementById('subscription-info');
            const plansContainer = document.getElementById('plans-container');
            const modal = new bootstrap.Modal(document.getElementById('payment-modal'));
            Loader.show();

            fetch("{{ url('api/get/plans/subscriptions') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        if (res.status === 403) {
                            alert('Unauthorized. Redirecting to login.');
                            window.location.href = '/';
                        } else {
                            const errorData = await res.json();
                            throw new Error(errorData.message || 'Something went wrong');
                        }
                    }
                    return res.json();
                })
                .then(data => {
                    Loader.hide();

                    const subscription = data.data.subscription;
                    const plans = data.data.plans;

                    if (subscription) {
                        subscriptionInfo.innerHTML = `
                                                                                                                <div class="subscription-info">
                                                                                                                    <div class="d-flex justify-content-between">
                                                                                                                        <h2>Current Subscription: ${subscription.type}</h2>
                                                                                                                        <button id="cancel-subscription"
                                                                                                                            class="btn btn-outline-danger"
                                                                                                                            data-company-id="${subscription.company_id}">
                                                                                                                            Cancel
                                                                                                                        </button>
                                                                                                                    </div>
                                                                                                                    <p><strong>Status:</strong> ${subscription.stripe_status ?? 'expired'}</p>
                                                                                                                </div>
                                                                                                            `;


                        const cancelBtn = document.getElementById('cancel-subscription');
                        cancelBtn.addEventListener('click', function() {
                            const companyId = this.getAttribute('data-company-id');

                            fetch(`/api/admin/plans/${companyId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Authorization': 'Bearer ' + token
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    alert(data.message);
                                    location.reload();
                                })
                                .catch(err => console.error(err))
                                .finally(() => {});
                        });
                    }

                    plans.forEach(plan => {
                        const card = document.createElement('div');
                        card.className = 'col-md-4 mb-4';
                        card.innerHTML =
                            `
                                                                                            <div class="card h-100 shadow-sm">
                                                                                            <div class="card-body d-flex flex-column" >
                                                                                            <h5 class="card-title">
                                                                                        ${plan.name} - ${plan.duration.charAt(0).toUpperCase() + plan.duration.slice(1)}
                                                                                            </h5>
                                                                                            <h6 class="card-subtitle mb-2 text-muted">$${plan.price}</h6>
                                                                                            <p class="card-text">
                                                                                        ${plan.duration === 'monthly' ? 'Billed monthly' : 'Billed yearly'}                             
                                                                                            </p>
                                                                                            <form class="mt-auto subscribe-form">
                                                                                        <button type="submit" class="btn btn-primary w-100" data-plan-id="${plan.id}">Subscribe</button>
                                                                                            </form>
                                                                                            </div>
                                                                                            </div>
                                                                                                                                                                                `;
                        plansContainer.appendChild(card);
                    });

                    document.querySelectorAll('.subscribe-form button').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();

                            selectedPlanId = this.dataset.planId;

                            // document.getElementById('payment-modal').style.display = 'block';
                            modal.show();


                            if (!cardElement) {
                                cardElement = elements.create('card');
                                cardElement.mount('#card-element');
                            }
                        });
                    });

                })
                .catch(err => console.error(err));

            document.getElementById('confirm-payment').addEventListener('click', async function() {
                const token = localStorage.getItem('sanctum_token');

                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });

                if (error) {
                    document.getElementById('card-errors').innerText = error.message;
                    return;
                }
                console.log('planid '.selectedPlanId);
                Loader.show();
                fetch('/api/subscribe', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({
                            plan_id: selectedPlanId,
                            payment_method: paymentMethod.id
                        })
                    })
                    .then(res => res.json())
                    .then(resp => {

                        alert(resp.message);
                        // location.reload();
                    })
                    .catch(err => console.error(err));
            });
            document.getElementById('close-payment').addEventListener('click', function() {
                // document.getElementById('payment-modal').style.display = 'none';
                modal.close();

            });


        });
    </script>
@endsection
