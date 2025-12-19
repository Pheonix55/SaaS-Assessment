{{-- @extends('layouts.app1')
@section('content')
    <div class="full_container">
        <div class="inner_container">


            <div class="midde_cont">
                <div class="container-fluid">
                    <div class="row column_title">
                        <div class="col-md-12">
                            <div class="page_title">
                                <h2>Price Table</h2>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                    <div class="container-fluid">
                        <div class="row" id="subscription-section" style="display:none;">
                            <div class="col-md-12">
                                <div class="white_shd full margin_bottom_30">
                                    <div class="full graph_head">
                                        <div class="heading1 margin_0 d-flex justify-content-between align-items-center">
                                            <h2>Current Subscription</h2>

                                            <button id="cancel-subscription" class="btn btn-danger btn-sm">
                                                Cancel Subscription
                                            </button>
                                        </div>
                                    </div>

                                    <div class="full inner_elements">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Plan:</strong> <span id="sub-plan">—</span></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Status:</strong> <span id="sub-status">—</span></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Billing Cycle:</strong> <span id="sub-cycle">—</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row column1">
                            <div class="col-md-12">
                                <div class="white_shd full margin_bottom_30">
                                    <div class="full graph_head">
                                        <div class="heading1 margin_0">
                                            <h2>Pricing Table</h2>
                                        </div>
                                    </div>
                                    <div class="full price_table padding_infor_info">
                                        <div class="row" id="plans-container"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Modal -->
                <div class="modal fade" id="payment-modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Enter Card Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    X</button>
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

            @section('scripts')
                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    const stripe = Stripe("{{ config('cashier.key') }}");
                    const elements = stripe.elements();

                    let cardElement = null;
                    let selectedPlanId = null;
                    let paymentModal = null;
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {




                        const token = localStorage.getItem('sanctum_token');

                        const plansContainer = document.getElementById('plans-container');




                        paymentModal = new bootstrap.Modal(
                            document.getElementById('payment-modal')
                        );

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
                                    }
                                    const error = await res.json();
                                    throw new Error(error.message || 'Failed to load plans');
                                }
                                return res.json();
                            })
                            .then(resp => {
                                Loader.hide();

                                const subscription = resp.data.subscription;

                                // Handle active subscription
                                if (subscription) {
                                    document.getElementById('subscription-section').style.display = 'block';

                                    document.getElementById('sub-plan').innerText = subscription.type;
                                    document.getElementById('sub-status').innerText =
                                        subscription.stripe_status ?? 'expired';

                                    document.getElementById('sub-cycle').innerText =
                                        subscription.interval ?? '—';

                                    const cancelBtn = document.getElementById('cancel-subscription');
                                    cancelBtn.dataset.companyId = subscription.company_id;
                                }
                                document.getElementById('cancel-subscription')?.addEventListener('click', function() {

                                    if (!confirm('Are you sure you want to cancel this subscription?')) {
                                        return;
                                    }

                                    const token = localStorage.getItem('sanctum_token');
                                    const companyId = this.dataset.companyId;

                                    Loader.show();
                                    this.disabled = true;

                                    fetch(`/api/admin/plans/${companyId}`, {
                                            method: 'POST',
                                            headers: {
                                                'Accept': 'application/json',
                                                'Authorization': 'Bearer ' + token
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(resp => {
                                            Loader.hide();
                                            alert(resp.message || 'Subscription cancelled');
                                            location.reload();
                                        })
                                        .catch(err => {
                                            Loader.hide();
                                            this.disabled = false;
                                            console.error(err);
                                            alert('Failed to cancel subscription');
                                        });
                                });

                                // Existing plan rendering logic continues here…


                                const plans = resp.data.plans;

                                if (!plans || plans.length === 0) {
                                    plansContainer.innerHTML = '<p>No plans available.</p>';
                                    return;
                                }


                                plans.forEach(plan => {
                                    const col = document.createElement('div');
                                    col.className = 'col-lg-3 col-md-6 col-sm-6 col-xs-12';

                                    col.innerHTML = `
                <div class="table_price full">
                    <div class="inner_table_price">
                        <div class="price_table_head blue1_bg">
                            <h2>${plan.name}</h2>
                        </div>

                        <div class="price_table_inner">
                            <div class="cont_table_price_blog">
                                <p class="blue1_color">
                                    $ <span class="price_no">${plan.price}</span>
                                    ${plan.duration.charAt(0).toUpperCase() + plan.duration.slice(1)}
                                </p>
                            </div>

                            <div class="cont_table_price">
                                <ul>
                                    <li><a href="#">Access to features</a></li>
                                    <li><a href="#">Email support</a></li>
                                    <li><a href="#">Regular updates</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="price_table_bottom">
                            <div class="center">
                                <button 
                                    class="main_bt subscribe-btn"
                                    data-plan-id="${plan.id}" >
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                                    plansContainer.appendChild(col);

                                    if (subscription && subscription.plan_id === plan.id) {
                                        col.querySelector('.subscribe-btn').disabled = true;
                                        col.querySelector('.subscribe-btn').innerText = 'Active';
                                    }
                                });
                            })
                            .catch(err => {
                                Loader.hide();
                                console.error(err);
                                alert(err.message);
                            });

                        document.addEventListener('click', function(e) {
                            if (!e.target.classList.contains('subscribe-btn')) return;

                            e.preventDefault();

                            selectedPlanId = e.target.dataset.planId;

                            paymentModal.show();

                            if (!cardElement) {
                                cardElement = elements.create('card', {
                                    hidePostalCode: true
                                });
                                cardElement.mount('#card-element');
                            }
                        });

                        document.getElementById('confirm-payment')
                            .addEventListener('click', async function() {

                                if (!selectedPlanId) {
                                    alert('No plan selected.');
                                    return;
                                }

                                const token = localStorage.getItem('sanctum_token');
                                const errorBox = document.getElementById('card-errors');
                                errorBox.innerText = '';

                                Loader.show();
                                this.disabled = true;

                                const {
                                    paymentMethod,
                                    error
                                } = await stripe.createPaymentMethod({
                                    type: 'card',
                                    card: cardElement
                                });

                                if (error) {
                                    Loader.hide();
                                    this.disabled = false;
                                    errorBox.innerText = error.message;
                                    return;
                                }

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
                                        Loader.hide();
                                        this.disabled = false;

                                        if (!resp.success) {
                                            errorBox.innerText = resp.message || 'Subscription failed.';
                                            return;
                                        }

                                        alert(resp.message || 'Subscription successful!');
                                        paymentModal.hide();
                                        location.reload();
                                    })
                                    .catch(err => {
                                        Loader.hide();
                                        this.disabled = false;
                                        console.error(err);
                                        errorBox.innerText = 'Unexpected error occurred.';
                                    });
                            });

                    });
                </script>
            @endsection
        @endsection --}}

@extends('layouts.app1')

@section('title', 'Pricing Plans')

@section('content')
    <div class="full_container">
        <div class="inner_container">

            <div class="midde_cont">
                <div class="container-fluid">
                    <div class="row column_title">
                        <div class="col-md-12">
                            <div class="page_title">
                                <h2>Price Table</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Current Subscription -->
                    <div class="row" id="subscription-section" style="display:none;">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <div class="full graph_head">
                                    <div class="heading1 margin_0 d-flex justify-content-between align-items-center w-100">
                                        <h2>Current Subscription</h2>
                                        <button id="cancel-subscription" class="btn btn-danger btn-sm">
                                            Cancel Subscription
                                        </button>
                                    </div>
                                </div>
                                <div class="full inner_elements py-3">
                                    <div class="row px-4">
                                        <div class="col-md-3">
                                            <p><strong>Plan:</strong> <span id="sub-plan">—</span></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Status:</strong> <span id="sub-status">—</span></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Billing Cycle:</strong> <span id="sub-cycle">—</span></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Table -->
                    <div class="row column1">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <div class="full graph_head">
                                    <div class="heading1 margin_0">
                                        <h2>Pricing Table</h2>
                                    </div>
                                </div>
                                <div class="full price_table padding_infor_info">
                                    <div class="row" id="plans-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Payment Modal -->
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
                            <button id="confirm-payment" class="btn btn-success">Confirm Subscription</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe("{{ config('cashier.key') }}");
            const elements = stripe.elements();
            let cardElement = null;
            let selectedPlanId = null;
            const paymentModal = new bootstrap.Modal(document.getElementById('payment-modal'));
            const token = localStorage.getItem('sanctum_token');

            const plansContainer = document.getElementById('plans-container');

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
                        }
                        const error = await res.json();
                        throw new Error(error.message || 'Failed to load plans');
                    }
                    return res.json();
                })
                .then(resp => {
                    Loader.hide();

                    const subscription = resp.data.subscription;

                    const plans = resp.data.plans;
                    const currentPlan = resp.data.currentPlan;


                    // Show subscription info
                    if (subscription && currentPlan) {
                        document.getElementById('subscription-section').style.display = 'block';
                        document.getElementById('sub-plan').innerText = subscription.type;
                        document.getElementById('sub-status').innerText = subscription.stripe_status ??
                            'expired';
                        document.getElementById('sub-cycle').innerText = currentPlan?.duration ?? '—';
                        document.getElementById('cancel-subscription').dataset.companyId = subscription
                            .company_id;
                    }

                    // Cancel subscription
                    document.getElementById('cancel-subscription')?.addEventListener('click', function() {
                        if (!confirm('Are you sure you want to cancel this subscription?')) return;

                        const companyId = this.dataset.companyId;
                        this.disabled = true;
                        Loader.show();

                        fetch(`/api/admin/plans/${companyId}`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': 'Bearer ' + token
                                }
                            })
                            .then(res => res.json())
                            .then(resp => {
                                Loader.hide();
                                alert(resp.message || 'Subscription cancelled');
                                location.reload();
                            })
                            .catch(err => {
                                Loader.hide();
                                this.disabled = false;
                                console.error(err);
                                alert('Failed to cancel subscription');
                            });
                    });

                    // Render plans
                    if (!plans || plans.length === 0) {
                        plansContainer.innerHTML = '<p>No plans available.</p>';
                        return;
                    }

                    plans.forEach(plan => {
                        const col = document.createElement('div');
                        col.className = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
                        col.innerHTML = `
                <div class="table_price full">
                    <div class="inner_table_price">
                        <div class="price_table_head blue1_bg">
                            <h2>${plan.name}</h2>
                        </div>
                        <div class="price_table_inner">
                            <div class="cont_table_price_blog">
                                <p class="blue1_color">$ <span class="price_no">${plan.price}</span> ${plan.duration.charAt(0).toUpperCase() + plan.duration.slice(1)}</p>
                            </div>
                            <div class="cont_table_price">
                                <ul>
                                    <li><a href="#">Access to features</a></li>
                                    <li><a href="#">Email support</a></li>
                                    <li><a href="#">Regular updates</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="price_table_bottom">
                            <div class="center">
                                <button class="main_bt subscribe-btn" data-plan-id="${plan.id}">Buy Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                        plansContainer.appendChild(col);

                        if (subscription && currentPlan && plan) {
                            if (Number(currentPlan.id) === Number(plan.id)) {
                                const btn = col.querySelector('.subscribe-btn');

                                if (btn) {
                                    btn.disabled = true;
                                    btn.textContent = 'Active';
                                    btn.classList.remove('btn-primary');
                                    btn.classList.add('btn-danger');
                                }
                            }
                        }

                    });
                })
                .catch(err => {
                    Loader.hide();
                    console.error(err);
                    alert(err.message);
                });

            // Subscribe button click
            document.addEventListener('click', function(e) {
                if (!e.target.classList.contains('subscribe-btn')) return;
                e.preventDefault();
                selectedPlanId = e.target.dataset.planId;
                paymentModal.show();

                if (!cardElement) {
                    cardElement = elements.create('card', {
                        hidePostalCode: true
                    });
                    cardElement.mount('#card-element');
                }
            });

            // Confirm payment
            document.getElementById('confirm-payment').addEventListener('click', async function() {
                if (!selectedPlanId) {
                    alert('No plan selected.');
                    return;
                }

                const errorBox = document.getElementById('card-errors');
                errorBox.innerText = '';
                this.disabled = true;
                Loader.show();

                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement
                });

                if (error) {
                    Loader.hide();
                    this.disabled = false;
                    errorBox.innerText = error.message;
                    return;
                }

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
                    .then(async res => {
                        const resp = await res.json();
                        Loader.hide();
                        this.disabled = false;

                        console.log(resp);
                        // check if stripe requires SCA
                        if (resp.requires_action && resp.payment_intent_client_secret) {
                            const result = await stripe.confirmCardPayment(resp
                                .payment_intent_client_secret);
                            if (result.error) {
                                // Payment failed
                                errorBox.innerText = result.error.message ||
                                    'Payment authentication failed.';
                                return;
                            } else {
                                window.location.reload();
                                return;
                            }
                        }
                        if (resp.invoice_download_url) {
                            window.location.href = resp.invoice_download_url;
                            return;
                        }
                        if (!resp.success) {
                            errorBox.innerText = resp.message || 'Subscription failed.';
                            return;
                        }
                        alert(resp.message || 'Subscription successful!');
                        paymentModal.hide();
                        window.location.reload();
                    })
                    .catch(err => {
                        Loader.hide();
                        this.disabled = false;
                        console.error(err);
                        errorBox.innerText = 'Unexpected error occurred.';
                    });
            });
        });
    </script>
@endsection
