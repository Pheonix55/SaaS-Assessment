@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div>
            <table class="table table-bordered py-3" id="invitations-table">
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


        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Invite Team Member</div>

                    <div class="card-body">
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
    </div>
    <script>
        fetch("{{ url('/api/list/roles') }}", {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load roles');
                }
                return response.json();
            })
            .then(data => {
                const select = document.getElementById('role-select');

                data.roles.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role.name;
                    option.textContent = role.name;
                    select.appendChild(option);
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        document.getElementById('invite-form').addEventListener('submit', function (e) {
            e.preventDefault();

            document.getElementById('email-error').innerText = '';
            document.getElementById('role-error').innerText = '';

            const form = this;
            const formData = new FormData(form);

            fetch("{{ url('/api/invite-user') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                },
                body: formData
            })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        throw data;
                    }

                    return data;
                })
                .then(data => {
                    alert(data.message);
                    form.reset();
                })
                .catch(error => {

                    if (error.errors) {
                        if (error.errors.email) {
                            document.getElementById('email-error').innerText = error.errors.email[0];
                        }
                        if (error.errors.role) {
                            document.getElementById('role-error').innerText = error.errors.role[0];
                        }
                    }

                    if (error.message === 'Unauthorized') {
                        window.location.href = '/';
                    }
                });
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadInvitations();
        });

        function loadInvitations() {
            fetch("{{ url('/api/admin/get-invitations') }}", {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                }
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw data;
                    return data;
                })
                .then(result => {
                    const tbody = document.querySelector('#invitations-table tbody');
                    tbody.innerHTML = '';

                    if (!result.data.length) {
                        tbody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="text-center">No invitations found</td>
                                </tr>`;
                        return;
                    }

                    result.data.forEach((invite, index) => {
                        tbody.insertAdjacentHTML('beforeend', `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${invite.email}</td>
                                    <td>${invite.new_role}</td>
                                    <td>${invite.inviter?.name ?? '-'}</td>
                                    <td>${invite.company?.name ?? '-'}</td>
                                    <td>
                                        <span class="badge bg-${statusColor(invite.status)}">
                                            ${invite.status}
                                        </span>
                                    </td>
                                    <td>${formatDate(invite.expiry_date)}</td>
                                </tr>
                            `);
                    });
                })
                .catch(error => {
                    if (error.message === 'company id doesnt exist') {
                        alert('Company not found');
                    } else {
                        console.error(error);
                    }
                });
        }

        function statusColor(status) {
            switch (status) {
                case 'pending': return 'warning';
                case 'accepted': return 'success';
                case 'expired': return 'secondary';
                case 'rejected': return 'danger';
                default: return 'light';
            }
        }

        function formatDate(date) {
            return new Date(date).toLocaleDateString();
        }

    </script>

@endsection