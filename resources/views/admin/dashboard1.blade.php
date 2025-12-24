@extends('layouts.app1')
@section('content')
    <div class="midde_cont py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="alert alert-info col-md-12 d-none" id="showCompanyApproval" role="alert">
                </div>
                <!-- Buttons for testing -->


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
                                        <td colspan="7" class="text-center">no users yet</td>

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
                                            <td colspan="7" class="text-center">no invitations</td>
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

                {{-- shubscripton audit --}}


                <div class="col-md-12">

                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head d-flex justify-content-between align-items-center">
                            <div class="heading1 margin_0">
                                <h2>Subscription History</h2>
                            </div>
                        </div>

                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                <table class="table table-hover" id="subscription-events-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>type</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td colspan="7" class="text-center">no subscription data availible</td>

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
                                        <td colspan="7" class="text-center">no transactions made yet</td>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head">
                            <div class="heading1 margin_0">
                                <h2>Audit Logs</h2>
                            </div>
                        </div>
                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                <table class="table table-hover" id="audit-logs-table">
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
                                        <td colspan="7" class="text-center">nothing in the audits log</td>

                                    </tbody>
                                </table>
                                <div class="mt-3 text-center" id="audit-pagination"></div>

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

            async function fetchUsers(page = 1) {
                try {
                    const data = await secureFetch(`/api/users?page=${page}`, {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    if (!data) {
                        showSweetAlert({
                            message: 'You are not approved by the system yet',
                            icon: 'warning'
                        });
                        return;
                    }

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
                    showSweetAlert({
                        message: 'Error fetching users.',
                        icon: 'error'
                    });
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

            async function fetchTransactions() {
                try {
                    const data = await secureFetch('/api/admin/get-transactions', {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    if (!data) return;

                    if (data.success) {
                        const tbody = document.querySelector('#transactions-table tbody');
                        tbody.innerHTML = '';
                        data.data.forEach(tx => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                        <td>${tx.id}</td>
                        <td>${tx.amount ?? '-'}</td>
                        <td>${tx.currency ?? '-'}</td>
                        <td>Stripe-visa</td>
                        <td>${new Date(tx.created_at).toLocaleString()}</td>
                        <td>
                            <a class="btn btn-outline-success" href="${tx.temp_invoice_url}">Download Invoice</a>
                        </td>
                    `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        showSweetAlert({
                            message: data.message || 'Failed to load transactions.',
                            icon: 'error'
                        });
                    }
                } catch (err) {
                    console.error(err);
                    showSweetAlert({
                        message: 'Error fetching transactions.',
                        icon: 'error'
                    });
                }
            }

            async function loadRoles() {
                try {
                    const data = await secureFetch("{{ url('/api/list/roles') }}", {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    if (!data) return;

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

            async function loadInvitations() {
                try {
                    const data = await secureFetch("{{ url('/api/admin/get-invitations') }}", {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    if (!data) return;

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
                    if (err.message === 'company id doesnt exist') {
                        showSweetAlert({
                            message: 'Company not found',
                            icon: 'warning'
                        });
                    } else console.error(err);
                }
            }

            fetchUsers();
            fetchTransactions();
            loadRoles();
            loadInvitations();
            Loader.hide();

            document.getElementById('open-invite-btn').addEventListener('click', () => {
                new bootstrap.Modal(document.getElementById('inviteModal')).show();
            });

            document.getElementById('invite-form').addEventListener('submit', async (e) => {
                Loader.show();
                e.preventDefault();
                clearErrors();

                const form = e.target;
                const formData = new FormData(form);

                try {
                    const data = await secureFetch("{{ url('/api/invite-user') }}", {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    if (!data) return;

                    Loader.hide();
                    showSweetAlert({
                        message: data.message,
                        icon: 'success'
                    });
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('inviteModal')).hide();
                    loadInvitations();
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
        });

        async function loadSubscriptionEvents() {
            try {
                const data = await secureFetch('/api/subscription/events', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                    }
                });
                if (!data) return;

                const tbody = document.querySelector('#subscription-events-table tbody');
                tbody.innerHTML = '';

                data.forEach(event => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td>${event.id}</td>
                <td>${event.type ?? '-'}</td>
                <td>${event.created_at}</td>
                <td>${event.invoice_url 
                    ? `<a href="${event.invoice_url}" target="_blank" class="btn btn-sm btn-primary">Download Invoice</a>` 
                    : '-'}
                </td>
            `;
                    tbody.appendChild(tr);
                });
            } catch (err) {
                console.error(err);
                showSweetAlert({
                    message: 'Unable to load subscription events.',
                    icon: 'error'
                });
            }
        }

        document.addEventListener('DOMContentLoaded', loadSubscriptionEvents);
    </script>

    <script>
        const AUDIT_API = '/api/admin/audit-logs';
        const token = localStorage.getItem('sanctum_token');

        async function loadAuditLogs(page = 1) {
            try {
                const res = await fetch(`${AUDIT_API}?page=${page}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });

                const result = await res.json();
                // console.log(result.success,result.logs.data);
                if (!result.success) {
                    window.showSweetAlert({
                        title: 'Error',
                        message: 'Failed to load audit logs',
                        icon: 'error'
                    });
                    return;
                }

                const logs = result.logs.data;
                const tbody = document.querySelector('#audit-logs-table tbody');
                tbody.innerHTML = '';

                if (!logs.length) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">No audit logs found</td>
                    </tr>
                `;
                    return;
                }

                logs.forEach(log => {
                    const amount = log.new_values?.amount ?? '-';
                    const currency = log.new_values?.currency ?? '-';
                    const paymentMethod = log.new_values?.payment_method ?? '-';
                    const createdAt = new Date(log.created_at).toLocaleString();

                    tbody.innerHTML += `
                    <tr>
                        <td>${log.id}</td>
                        <td>${amount}</td>
                        <td>${currency.toUpperCase()}</td>
                        <td>${paymentMethod}</td>
                        <td>${createdAt}</td>
                        <td>
                            <span class="badge bg-${getActionColor(log.action)}">
                                ${log.action.toUpperCase()}
                            </span>
                        </td>
                    </tr>
                `;
                });

                renderPagination(result.logs);

            } catch (err) {
                window.showSweetAlert({
                    title: 'Network Error',
                    message: err.message,
                    icon: 'error'
                });
            }
        }

        function getActionColor(action) {
            switch (action) {
                case 'created':
                    return 'success';
                case 'updated':
                    return 'warning';
                case 'deleted':
                    return 'danger';
                default:
                    return 'secondary';
            }
        }

        function renderPagination(pagination) {
            const container = document.getElementById('audit-pagination');
            if (!container) return;

            container.innerHTML = '';

            pagination.links.forEach(link => {
                if (!link.url) return;

                const btn = document.createElement('button');
                btn.innerHTML = link.label;
                btn.className = `btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-primary'} mx-1`;
                btn.onclick = () => {
                    const page = new URL(link.url).searchParams.get('page');
                    loadAuditLogs(page);
                };

                container.appendChild(btn);
            });
        }

        document.addEventListener('DOMContentLoaded', () => loadAuditLogs());
    </script>
@endsection
