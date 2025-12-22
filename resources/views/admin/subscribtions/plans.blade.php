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
                                <h2>Chose your Plan</h2>
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
                                        <h2>Availiable Plans</h2>
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

            function formatPricePkr(num) {
                if (num >= 1_000_000_000) {
                    return (num / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'B';
                }
                if (num >= 1_000_000) {
                    return (num / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
                }
                if (num >= 1_000) {
                    return (num / 1_000).toFixed(1).replace(/\.0$/, '') + 'k';
                }
                return num.toString();
            }
            // Load subscription plans
            (async function loadPlans() {
                try {
                    const resp = await secureFetch("{{ url('api/get/plans/subscriptions') }}", {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    Loader.hide();
                    if (!resp) return;

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
                    document.getElementById('cancel-subscription')?.addEventListener('click',
                        async function() {
                            const companyId = this.dataset.companyId;

                            await showSweetAlert({
                                title: 'Cancel Subscription?',
                                message: 'Are you sure you want to cancel this subscription?',
                                icon: 'warning',
                                buttons: [{
                                        text: 'Yes',
                                        color: 'swal2-confirm',
                                        showLoader: true,
                                        callback: async () => {
                                            try {
                                                const resp = await secureFetch(
                                                    `/api/admin/plans/${companyId}`, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Authorization': 'Bearer ' +
                                                                token,
                                                        }
                                                    });
                                                showSweetAlert({
                                                    message: resp
                                                        .message ||
                                                        'Subscription cancelled',
                                                    icon: 'success',
                                                    autoHide: true
                                                });

                                                location.reload();
                                            } catch (err) {
                                                console.error(err);
                                                showSweetAlert({
                                                    message: 'Failed to cancel subscription',
                                                    icon: 'error',
                                                    autoHide: true
                                                });
                                            }
                                        }
                                    },
                                    {
                                        text: 'No',
                                        color: 'swal2-cancel'
                                    }
                                ]
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
                <p class="blue1_color">
                    <span class="price_no">${formatPricePkr(plan.price)}</span> pkr /
                    <span class="" style="font-weight: 300;">
                        ${plan.duration.charAt(0).toUpperCase() + plan.duration.slice(1)}
                    </span>
                </p>
            </div>
            <div class="cont_table_price">
                <ul>
                    <li><a href="#" style="font-style: italic;">Access to features</a></li>
                    <li><a href="#" style="font-style: italic;">Email support</a></li>
                    <li><a href="#" style="font-style: italic;">Regular updates</a></li>
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

                        if (subscription && currentPlan && plan && Number(currentPlan.id) ===
                            Number(plan.id)) {
                            const btn = col.querySelector('.subscribe-btn');
                            if (btn) {
                                btn.disabled = true;
                                btn.textContent = 'Active';
                                btn.classList.remove('btn-primary');
                                btn.classList.add('btn-danger');
                            }
                        }
                    });
                } catch (err) {
                    Loader.hide();
                    console.error(err);
                    showSweetAlert({
                        message: err.message || 'Failed to load plans',
                        icon: 'error'
                    });
                }
            })();

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
                    showSweetAlert({
                        message: 'No plan selected.',
                        icon: 'warning'
                    });
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

                try {
                    const resp = await secureFetch('/api/subscribe', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            plan_id: selectedPlanId,
                            payment_method: paymentMethod.id
                        })
                    });
                    Loader.hide();
                    this.disabled = false;

                    if (!resp) return;

                    // Handle SCA
                    if (resp.requires_action && resp.payment_intent_client_secret) {
                        const result = await stripe.confirmCardPayment(resp
                            .payment_intent_client_secret);
                        if (result.error) {
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

                    showSweetAlert({
                        message: resp.message || 'Subscription successful!',
                        icon: 'success'
                    });
                    paymentModal.hide();
                    window.location.reload();
                } catch (err) {
                    Loader.hide();
                    this.disabled = false;
                    console.error(err);
                    errorBox.innerText = 'Unexpected error occurred.';
                }
            });
        });
    </script>

@endsection
