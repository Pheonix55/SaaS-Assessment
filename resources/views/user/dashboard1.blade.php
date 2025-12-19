@extends('layouts.app1')
@section('content')
    <div class="midde_cont py-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Users Table -->
                <div class="col-md-6">
                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head">
                            <div class="heading1 margin_0">
                                <h2>Users</h2>
                            </div>
                        </div>
                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                <table class="table table-hover" id="users-table">
                                    <thead>
                                        <tr>
                                            <th>name</th>
                                            <th>role</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Users will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="users-pagination" class="mt-2"></div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">

                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head d-flex justify-content-between align-items-center">
                            <div class="heading1 margin_0">
                                <h2>Invitations</h2>
                            </div>
                            <button class="btn btn-primary" id="open-invite-btn">Invite Team Member</button>
                        </div>

                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                <table class="table table-hover" id="invitations-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Invited By</th>
                                            <th>Company</th>
                                            <th>Status</th>
                                            <th>Expiry</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invite Modal -->
                <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="inviteModalLabel">Invite Team Member</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="invite-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                        <small class="text-danger" id="email-error"></small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select id="role-select" class="form-select" name="role">
                                            <option value="">Select Role</option>
                                        </select>
                                        <small class="text-danger" id="role-error"></small>
                                    </div>

                                    <button class="btn btn-primary" type="submit">Send Invitation</button>
                                </form>
                                <div class="alert alert-success mt-3 d-none" id="success-msg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head d-flex justify-content-between align-items-center">
                            <div class="heading1 margin_0">
                                <h2>Invitations</h2>
                            </div>
                            <button class="btn btn-primary" id="open-invite-btn">Invite Team Member</button>
                        </div>

                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                <table class="table table-hover" id="transactions-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Amount</th>
                                            <th>Currency</th>
                                            <th>Payment Method</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Transactions will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="col-md-12">
                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head">
                            <div class="heading1 margin_0">
                                <h2>Transactions</h2>
                            </div>
                        </div>
                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                <table class="table table-hover" id="transactions-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Amount</th>
                                            <th>Currency</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Payment Method</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Transactions will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('sanctum_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }
            Loader.show();

            // Fetch users
            async function fetchUsers(page = 1) {
                try {
                    const res = await fetch(`/api/users?page=${page}`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();

                    if (data.success) {
                        const tbody = document.querySelector('#users-table tbody');
                        tbody.innerHTML = '';
                        data.data.data.forEach(user => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                        <td>${user.name}</td>
                        <td>${user.role}</td>
                        <td>${user.email}</td>
                    `;
                            tbody.appendChild(tr);
                        });

                        renderPagination(data.data);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error fetching users.');
                }
            }

            function renderPagination(pagination) {
                const container = document.getElementById('users-pagination');
                container.innerHTML = '';
                for (let i = 1; i <= pagination.last_page; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.classList.add('btn', 'btn-sm', 'btn-primary', 'm-1');
                    if (i === pagination.current_page) btn.disabled = true;
                    btn.addEventListener('click', () => fetchUsers(i));
                    container.appendChild(btn);
                }
            }

            // // Fetch invitations
            // async function fetchInvites() {
            //     try {
            //         const res = await fetch('/api/admin/get-invitations', {
            //             headers: {
            //                 'Authorization': 'Bearer ' + token,
            //                 'Accept': 'application/json'
            //             }
            //         });
            //         const data = await res.json();

            //         if (data.success) {
            //             const tbody = document.querySelector('#invites-table tbody');
            //             tbody.innerHTML = '';
            //             data.data.forEach(invite => {
            //                 const tr = document.createElement('tr');
            //                 tr.innerHTML = `
        //             <td>${invite.inviter ? invite.inviter.name : '-'}</td>
        //             <td>${invite.email}</td>
        //             <td>${invite.status || '-'}</td>
        //         `;
            //                 tbody.appendChild(tr);
            //             });
            //         }
            //     } catch (err) {
            //         console.error(err);
            //         alert('Error fetching invitations.');
            //     }
            // }
            // Fetch transactions
            async function fetchTransactions() {
                try {
                    const res = await fetch('/api/admin/get-transactions', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await res.json();

                    if (data.success) {
                        const tbody = document.querySelector('#transactions-table tbody');
                        tbody.innerHTML = '';

                        data.data.forEach(tx => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                    <td>${tx.id}</td>
                    <td>${tx.amount ?? '-'}</td>
                    <td>${tx.currency ?? '-'}</td>
                    <td>${'Stripe-visa'}</td>
                    <td>${new Date(tx.created_at).toLocaleString()}</td>
                    <td><a class="btn btn-outline-success" id="download-invoice href="${tx.temp_invoice_url }">download invoice</button></td>

                `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        alert(data.message || 'Failed to load transactions.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error fetching transactions.');
                }
            }
            // Load roles
            async function loadRoles() {
                try {
                    const res = await fetch("{{ url('/api/list/roles') }}", {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error('Failed to load roles');

                    const select = document.getElementById('role-select');
                    data.roles.forEach(role => {
                        const option = document.createElement('option');
                        option.value = role.name;
                        option.textContent = role.name;
                        select.appendChild(option);
                    });
                } catch (err) {
                    console.error(err);
                }
            }

            // Load invitations
            async function loadInvitations() {
                try {
                    const res = await fetch("{{ url('/api/admin/get-invitations') }}", {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    const data = await res.json();
                    if (!data.success) throw data;

                    const tbody = document.querySelector('#invitations-table tbody');
                    tbody.innerHTML = '';

                    if (!data.data.length) {
                        tbody.innerHTML =
                            `<tr><td colspan="7" class="text-center">No invitations found</td></tr>`;
                        return;
                    }

                    data.data.forEach((invite, index) => {
                        tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${index + 1}</td>
                    <td>${invite.email}</td>
                    <td>${invite.new_role ?? '-'}</td>
                    <td>${invite.inviter?.name ?? '-'}</td>
                    <td>${invite.company?.name ?? '-'}</td>
                    <td><span class="badge bg-${statusColor(invite.status)}">${invite.status}</span></td>
                    <td>${formatDate(invite.expiry_date)}</td>
                </tr>
            `);
                    });

                } catch (err) {
                    if (err.message === 'company id doesnt exist') alert('Company not found');
                    else console.error(err);
                }
            }
            fetchUsers();
            // fetchInvites();
            fetchTransactions();

            loadRoles();
            loadInvitations();
            Loader.hide();

            // Open modal
            document.getElementById('open-invite-btn').addEventListener('click', () => {
                new bootstrap.Modal(document.getElementById('inviteModal')).show();
            });

            // Invite form submission

            document.getElementById('invite-form').addEventListener('submit', async (e) => {
                Loader.show();
                e.preventDefault();
                clearErrors();

                const form = e.target;
                const formData = new FormData(form);

                try {
                    const res = await fetch("{{ url('/api/invite-user') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await res.json();
                    if (!res.ok) throw data;
                    Loader.hide();
                    alert(data.message);
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('inviteModal')).hide();
                    loadInvitations(); // Refresh table
                } catch (err) {
                    if (err.errors) {
                        if (err.errors.email) document.getElementById('email-error').innerText = err
                            .errors.email[0];
                        if (err.errors.role) document.getElementById('role-error').innerText = err
                            .errors.role[0];
                    }
                    if (err.message === 'Unauthorized') window.location.href = '/';
                }
            });
        });



        // Helpers
        function statusColor(status) {
            switch (status) {
                case 'pending':
                    return 'warning';
                case 'accepted':
                    return 'success';
                case 'expired':
                    return 'secondary';
                case 'rejected':
                    return 'danger';
                default:
                    return 'light';
            }
        }

        function formatDate(date) {
            return date ? new Date(date).toLocaleDateString() : '-';
        }

        function clearErrors() {
            document.getElementById('email-error').innerText = '';
            document.getElementById('role-error').innerText = '';
        }
    </script>
@endsection
