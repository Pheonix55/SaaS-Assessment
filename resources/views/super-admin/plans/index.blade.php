@extends('layouts.super-admin', ['pageTitle' => __('Plans')])

@section('content')
    <div class="midde_cont py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="alert alert-info col-md-12 " id="showMessages" role="alert">
                    the plan was not created in test mode. Please make sure to switch to live mode in stripe dashboard and
                    create products/plans there as well.
                </div>
                <div class="col-md-12">

                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head d-flex justify-content-between align-items-center">
                            <div class="heading1 margin_0">
                                <h2>Subscription Plans</h2>
                            </div>
                            <button class="btn btn-primary" id="open-plan-btn">Create Plan</button>
                        </div>

                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                <table class="table table-hover" id="plans-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Amount (PKR)</th>
                                            <th>Interval</th>
                                            <th>Stripe Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3">
                                    <nav>
                                        <ul class="pagination" id="plans-pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create / Edit Plan Modal -->
    <div class="modal fade" id="planModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="plan-form">
                        @csrf
                        <input type="hidden" id="plan-id">

                        <div class="mb-3">
                            <label class="form-label">Plan Name</label>
                            <input type="text" id="plan-name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount (PKR)</label>
                            <input type="number" id="plan-amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Billing Interval</label>
                            <select id="plan-interval" class="form-select">
                                <option value="month">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">
                            Save Plan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const API_BASE = '/api/superadmin/stripe/products';
        const token = localStorage.getItem('sanctum_token');
        let currentPage = 1;

        const planModal = new bootstrap.Modal(document.getElementById('planModal'));

        document.getElementById('open-plan-btn').addEventListener('click', () => {
            resetForm();
            planModal.show();
        });

        function resetForm() {
            document.getElementById('plan-id').value = '';
            document.getElementById('plan-form').reset();
        }

        /* Load Plans */

        async function loadPlans(page = 1) {
            try {
                currentPage = page;

                const res = await fetch(`${API_BASE}?page=${page}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                if (!res.ok) {
                    throw new Error('Failed to load plans');
                }

                const result = await res.json();
                const pagination = result.plans;

                renderPlans(pagination.data);
                renderPagination(pagination);

            } catch (error) {
                showSweetAlert({
                    title: 'Error',
                    message: error.message,
                    icon: 'error'
                });
            }
        }

        function renderPlans(plans) {
            const tbody = document.querySelector('#plans-table tbody');
            tbody.innerHTML = '';

            if (!plans.length) {
                tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">No plans found</td>
            </tr>
        `;
                return;
            }

            plans.forEach((plan, index) => {
                tbody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${plan.name}</td>
                <td>${plan.price} PKR</td>
                <td>${plan.duration}</td>
                <td>${plan.stripe_product_id ?? '-'}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editPlan(${plan.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deletePlan(${plan.id})">Delete</button>
                </td>
            </tr>
        `;
            });
        }

        function renderPagination(pagination) {
            const ul = document.getElementById('plans-pagination');
            ul.innerHTML = '';

            if (pagination.last_page <= 1) return;

            // Previous
            ul.innerHTML += `
        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadPlans(${pagination.current_page - 1})">Previous</a>
        </li>
    `;

            for (let i = 1; i <= pagination.last_page; i++) {
                ul.innerHTML += `
            <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                <a class="page-link" href="#" onclick="loadPlans(${i})">${i}</a>
            </li>
        `;
            }

            // Next
            ul.innerHTML += `
        <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadPlans(${pagination.current_page + 1})">Next</a>
        </li>
    `;
        }

        // async function loadPlans() {
        //     try {
        //         const res = await fetch(API_BASE, {
        //             headers: {
        //                 Authorization: `Bearer ${token}`
        //             }
        //         });

        //         if (!res.ok) {
        //             throw new Error('Failed to load plans');
        //         }

        //         const result = await res.json();
        //         const plans = result.plans || [];

        //         const tbody = document.querySelector('#plans-table tbody');
        //         tbody.innerHTML = '';

        //         if (!plans.length) {
        //             tbody.innerHTML = `
    //         <tr>
    //             <td colspan="6" class="text-center">No plans found</td>
    //         </tr>
    //     `;
        //             return;
        //         }

        //         plans.forEach((plan, index) => {
        //             tbody.innerHTML += `
    //         <tr>
    //             <td>${index + 1}</td>
    //             <td>${plan.name}</td>
    //             <td>${plan.price} PKR</td>
    //             <td>${plan.duration}</td>
    //             <td>${plan.stripe_product_id ?? '-'}</td>
    //             <td>
    //                 <button class="btn btn-sm btn-warning" onclick="editPlan(${plan.id})">Edit</button>
    //                 <button class="btn btn-sm btn-danger" onclick="deletePlan(${plan.id})">Delete</button>
    //             </td>
    //         </tr>
    //     `;
        //         });

        //     } catch (error) {
        //         showSweetAlert({
        //             title: 'Error',
        //             message: error.message,
        //             icon: 'error'
        //         });
        //     }
        // }

        // /* Create / Update */
        document.getElementById('plan-form').addEventListener('submit', async e => {
            e.preventDefault();

            const id = document.getElementById('plan-id').value;
            const payload = {
                name: document.getElementById('plan-name').value,
                amount: document.getElementById('plan-amount').value,
                interval: document.getElementById('plan-interval').value
            };

            const url = id ? `${API_BASE}/${id}` : API_BASE;
            const method = id ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: `Bearer ${token}`
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || 'Operation failed');
                }

                planModal.hide();
                loadPlans();

                showSweetAlert({
                    title: 'Success',
                    message: id ? 'Plan updated successfully' : 'Plan created successfully',
                    icon: 'success',
                    autoHide: true
                });

            } catch (error) {
                showSweetAlert({
                    title: 'Error',
                    message: error.message,
                    icon: 'error'
                });
            }
        });

        /* Edit */
        async function editPlan(id) {
            try {
                const res = await fetch(API_BASE, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                if (!res.ok) {
                    throw new Error('Failed to fetch plan');
                }

                const result = await res.json();
                console.log(result);
                const plan = result.plans.data.find(p => p.id === id);

                if (!plan) {
                    throw new Error('Plan not found');
                }

                document.getElementById('plan-id').value = plan.id;
                document.getElementById('plan-name').value = plan.name;
                document.getElementById('plan-amount').value = plan.price;
                document.getElementById('plan-interval').value = plan.duration;

                planModal.show();

            } catch (error) {
                showSweetAlert({
                    title: 'Error',
                    message: error.message,
                    icon: 'error'
                });
            }
        }

        /* Delete */
        async function deletePlan(id) {
            showSweetAlert({
                title: 'Confirm Deletion',
                message: 'This plan will be permanently deleted.',
                icon: 'warning',
                buttons: [{
                        text: 'Cancel',
                        color: 'swal2-cancel'
                    },
                    {
                        text: 'Delete',
                        color: 'swal2-confirm',
                        showLoader: true,
                        callback: async () => {
                            try {
                                const res = await fetch(`${API_BASE}/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        Authorization: `Bearer ${token}`
                                    }
                                });

                                if (!res.ok) {
                                    throw new Error('Failed to delete plan');
                                }

                                loadPlans(currentPage);

                                showSweetAlert({
                                    title: 'Deleted',
                                    message: 'Plan deleted successfully',
                                    icon: 'success',
                                    autoHide: true
                                });

                            } catch (error) {
                                showSweetAlert({
                                    title: 'Error',
                                    message: error.message,
                                    icon: 'error'
                                });
                            }
                        }
                    }
                ]
            });
        }

        document.addEventListener('DOMContentLoaded', loadPlans);
    </script>
@endsection
