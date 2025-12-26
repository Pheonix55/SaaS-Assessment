@extends('layouts.super-admin', ['pageTitle' => __('Dashboard')])

@section('content')
    <div class="midde_cont">
        <div class="container-fluid">
            <div class="row column_title">
                <div class="col-md-12">
                    <div class="page_title">
                        <h2>Dashboard</h2>
                    </div>
                </div>
            </div>
            <div class="row column1">
                <div class="col-md-6 col-lg-3">
                    <div class="full counter_section margin_bottom_30">
                        <div class="couter_icon">
                            <div>
                                <i class="fa fa-user yellow_color"></i>
                            </div>
                        </div>
                        <div class="counter_no">
                            <div class="d-flex g-1 justify-center align-items-start flex-column">
                                <p class="total_no text-black" id="total-companies">0</p>

                                <p class="head_couter text-black">Total companies</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="full counter_section margin_bottom_30">
                        <div class="couter_icon">
                            <div>
                                <i class="fa fa-clock-o blue1_color"></i>
                            </div>
                        </div>
                        <div class="counter_no">
                            <div class="d-flex g-1 justify-center align-items-start flex-column">
                                <p class="total_no text-black" id="active-companies">0</p>
                                <p class="head_couter text-black">Active Companies</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="full counter_section margin_bottom_30">
                        <div class="couter_icon">
                            <div>
                                <i class="fa fa-cloud-download green_color"></i>
                            </div>
                        </div>
                        <div class="counter_no">
                            <div class="d-flex g-1 justify-center align-items-start flex-column">
                                <p class="total_no text-black" id="pending-companies">0</p>
                                <p class="head_couter text-black">Pending Companies</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="full counter_section margin_bottom_30">
                        <div class="couter_icon">
                            <div>
                                <i class="fa fa-comments-o red_color"></i>
                            </div>
                        </div>
                        <div class="counter_no">
                            <div class="d-flex g-1 justify-center align-items-start flex-column">
                                <p class="total_no text-black" id="total-users">0</p>
                                <p class="head_couter text-black">Total Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row column1 social_media_section">
                <div class="col-md-6 col-lg-3">
                    <div class="full socile_icons fb margin_bottom_30">
                        <div class="social_icon">
                            <i class="fa fa-dollar"></i>
                        </div>
                        <div class="social_cont">
                            <ul>
                                <li>
                                    <span><strong id="total-revenue">0</strong></span>
                                    <span>Revenue</span>
                                </li>
                                {{-- <li>
                                    <span><strong>128</strong></span>
                                    <span>Feeds</span>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="full socile_icons tw margin_bottom_30">
                        <div class="social_icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="social_cont">
                            <ul>
                                <li>
                                    <span><strong id="new-invitations">0</strong>
                                    </span>
                                    <span>Invitations</span>
                                </li>
                                {{-- <li>
                                    <span><strong>978</strong></span>
                                    <span>Tweets</span>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="full socile_icons linked margin_bottom_30">
                        <div class="social_icon">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div class="social_cont">
                            <ul>
                                <li>
                                    <span><strong id="subscriptions-count">0</strong></span>
                                    <span>Susbcriptions</span>
                                </li>
                                {{-- <li>
                                    <span><strong>365</strong></span>
                                    <span>Feeds</span>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="full socile_icons google_p margin_bottom_30">
                        <div class="social_icon">
                            <i class="fa fa-dollar"></i>
                        </div>
                        <div class="social_cont">
                            <ul>
                                <li>
                                    <span><strong id="transactions-count">0</strong></span>
                                    <span>Transactions</span>
                                </li>
                                {{-- <li>
                                    <span><strong>57</strong></span>
                                    <span>Circles</span>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row column3">

                <div class="col-md-12">
                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head">
                            <div class="heading1 margin_0">
                                <h2>Audit Logs</h2>
                            </div>
                        </div>
                        <div class="table_section padding_infor_info">
                            <div class="table-responsive-sm">
                                {{-- <table class="table table-hover" id="transactions-table">
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
                                <div class="mt-3 text-center" id="audit-pagination"></div> --}}

                                <table class="table table-bordered" id="audit-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Entity</th>
                                            <th>Action</th>
                                            <th>Performed By</th>
                                            <th>Summary</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <div id="audit-pagination" class="mt-3"></div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="dash_blog">
                        <div class="dash_blog_inner">
                            <div class="dash_head">
                                <h3><span><i class="fa fa-comments-o"></i> Updates</span><span class="plus_green_bt"><a
                                            href="#">+</a></span></h3>
                            </div>
                            <div class="list_cont">
                                <p>User confirmation</p>
                            </div>
                            <div class="msg_list_main">
                                <ul class="task_list" id="today-tasks"></ul>


                                {{-- <ul class="msg_list">
                                    <li>
                                        <span><img src="images/layout_img/msg2.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">Herman Beck</span>
                                            <span class="msg_user">Sed ut perspiciatis unde omnis.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span><img src="images/layout_img/msg3.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">John Smith</span>
                                            <span class="msg_user">On the other hand, we denounce.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span><img src="images/layout_img/msg2.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">John Smith</span>
                                            <span class="msg_user">Sed ut perspiciatis unde omnis.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span><img src="images/layout_img/msg3.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">John Smith</span>
                                            <span class="msg_user">On the other hand, we denounce.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                </ul> --}}
                {{-- </div>
                            <div class="read_more">
                                <div class="center"><a class="main_bt read_bt" href="#">Read
                                        More</a></div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- testimonial -->
                {{-- <div class="col-md-6">
                    <div class="dark_bg full margin_bottom_30">
                        <div class="full graph_head">
                            <div class="heading1 margin_0">
                                <h2>Testimonial</h2>
                            </div>
                        </div>
                        <div class="full graph_revenue">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="content testimonial">
                                        <div id="testimonial_slider" class="carousel slide" data-ride="carousel">
                                            <!-- Wrapper for carousel items -->
                                            <div class="carousel-inner">
                                                <div class="item carousel-item active">
                                                    <div class="img-box"><img src="images/layout_img/user_img.jpg"
                                                            alt=""></div>
                                                    <p class="testimonial">Sed ut perspiciatis unde omnis iste natus error
                                                        sit voluptatem accusantium doloremque laudantium, totam rem aperiam,
                                                        eaque ipsa quae..</p>
                                                    <p class="overview"><b>Michael Stuart</b>Seo Founder</p>
                                                </div>
                                                <div class="item carousel-item">
                                                    <div class="img-box"><img src="images/layout_img/user_img.jpg"
                                                            alt=""></div>
                                                    <p class="testimonial">Sed ut perspiciatis unde omnis iste natus error
                                                        sit voluptatem accusantium doloremque laudantium, totam rem aperiam,
                                                        eaque ipsa quae..</p>
                                                    <p class="overview"><b>Michael Stuart</b>Seo Founder</p>
                                                </div>
                                                <div class="item carousel-item">
                                                    <div class="img-box"><img src="images/layout_img/user_img.jpg"
                                                            alt=""></div>
                                                    <p class="testimonial">Sed ut perspiciatis unde omnis iste natus error
                                                        sit voluptatem accusantium doloremque laudantium, totam rem aperiam,
                                                        eaque ipsa quae..</p>
                                                    <p class="overview"><b>Michael Stuart</b>Seo Founder</p>
                                                </div>
                                            </div>
                                            <!-- Carousel controls -->
                                            <a class="carousel-control left carousel-control-prev"
                                                href="#testimonial_slider" data-slide="prev">
                                                <i class="fa fa-angle-left"></i>
                                            </a>
                                            <a class="carousel-control right carousel-control-next"
                                                href="#testimonial_slider" data-slide="next">
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- end testimonial -->
                <!-- progress bar -->
                {{-- <div class="col-md-6">
                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head">
                            <div class="heading1 margin_0">
                                <h2>Progress Bar</h2>
                            </div>
                        </div>
                        <div class="full progress_bar_inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="progress_bar">
                                        <!-- Skill Bars -->
                                        <span class="skill" style="width:73%;">Facebook <span
                                                class="info_valume">73%</span></span>
                                        <div class="progress skill-bar ">
                                            <div class="progress-bar progress-bar-animated progress-bar-striped"
                                                role="progressbar" aria-valuenow="73" aria-valuemin="0"
                                                aria-valuemax="100" style="width: 73%;">
                                            </div>
                                        </div>
                                        <span class="skill" style="width:62%;">Twitter <span
                                                class="info_valume">62%</span></span>
                                        <div class="progress skill-bar">
                                            <div class="progress-bar progress-bar-animated progress-bar-striped"
                                                role="progressbar" aria-valuenow="62" aria-valuemin="0"
                                                aria-valuemax="100" style="width: 62%;">
                                            </div>
                                        </div>
                                        <span class="skill" style="width:54%;">Instagram <span
                                                class="info_valume">54%</span></span>
                                        <div class="progress skill-bar">
                                            <div class="progress-bar progress-bar-animated progress-bar-striped"
                                                role="progressbar" aria-valuenow="54" aria-valuemin="0"
                                                aria-valuemax="100" style="width: 54%;">
                                            </div>
                                        </div>
                                        <span class="skill" style="width:82%;">Google plus <span
                                                class="info_valume">82%</span></span>
                                        <div class="progress skill-bar">
                                            <div class="progress-bar progress-bar-animated progress-bar-striped"
                                                role="progressbar" aria-valuenow="82" aria-valuemin="0"
                                                aria-valuemax="100" style="width: 82%;">
                                            </div>
                                        </div>
                                        <span class="skill" style="width:48%;">Other <span
                                                class="info_valume">48%</span></span>
                                        <div class="progress skill-bar">
                                            <div class="progress-bar progress-bar-animated progress-bar-striped"
                                                role="progressbar" aria-valuenow="48" aria-valuemin="0"
                                                aria-valuemax="100" style="width: 48%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- end progress bar -->
            </div>
            <div class="row column3">
                <div class="col-md-6 margin_bottom_30">
                    <div class="dash_blog">
                        <div class="dash_blog_inner">
                            <div class="dash_head">
                                <h3>
                                    <span><i class="fa fa-calendar"></i> Company Approvals</span>
                                    <span class="plus_green_bt"><a href="#">+</a></span>
                                </h3>
                            </div>
                            <div class="list_cont">
                                <p></p>
                            </div>
                            <div class="task_list_main">
                                <ul class="task_list" id="approvals-list">
                                    <!-- Approval items will be loaded here dynamically -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dash_blog">
                        <div class="dash_blog_inner">
                            <div class="dash_head">
                                <h3><span><i class="fa fa-comments-o"></i> Updates</span><span class="plus_green_bt"><a
                                            href="#">+</a></span></h3>
                            </div>
                            <div class="list_cont">
                                <p>User confirmation</p>
                            </div>
                            <div class="msg_list_main">
                                <ul class="msg_list" id="recent-updates"></ul>

                                {{-- <ul class="msg_list">
                                    <li>
                                        <span><img src="images/layout_img/msg2.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">Herman Beck</span>
                                            <span class="msg_user">Sed ut perspiciatis unde omnis.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span><img src="images/layout_img/msg3.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">John Smith</span>
                                            <span class="msg_user">On the other hand, we denounce.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span><img src="images/layout_img/msg2.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">John Smith</span>
                                            <span class="msg_user">Sed ut perspiciatis unde omnis.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span><img src="images/layout_img/msg3.png" class="img-responsive"
                                                alt="#" /></span>
                                        <span>
                                            <span class="name_user">John Smith</span>
                                            <span class="msg_user">On the other hand, we denounce.</span>
                                            <span class="time_ago">12 min ago</span>
                                        </span>
                                    </li>
                                </ul> --}}
                            </div>
                            <div class="read_more">
                                <div class="center"><a class="main_bt read_bt" href="#">Read
                                        More</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer -->
                <div class="container-fluid">
                    <div class="footer">
                        <p>Copyright © 2018 Designed by html.design. All rights reserved.</p>
                    </div>
                </div>
            </div>
        @endsection

        @section('scripts')
            <script>
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
                async function loadDashboardData() {
                    try {
                        const res = await fetch("{{ url('/api/superadmin/dashboard/data') }}", {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                            }
                        });

                        const data = await res.json();

                        // Counters
                        document.getElementById('total-companies').innerText = data.total_companies;
                        document.getElementById('active-companies').innerText = data.active_companies;
                        document.getElementById('pending-companies').innerText = data.pending_companies;
                        document.getElementById('total-users').innerText = data.total_users;

                        // Social / stats
                        revenue = formatPricePkr(data.total_revenue);
                        document.getElementById('total-revenue').innerText = `Rs${revenue}`;
                        document.getElementById('new-invitations').innerText = data.new_invitations;
                        document.getElementById('subscriptions-count').innerText = data.subscriptions_count;
                        document.getElementById('transactions-count').innerText = data.transactions_count;

                        // Today tasks
                        //             const tasksEl = document.getElementById('today-tasks');
                        //             tasksEl.innerHTML = '';
                        //             data.today_tasks.forEach(task => {
                        //                 tasksEl.insertAdjacentHTML('beforeend', `
            //     <li>
            //         <a href="#">${task}</a><br>
            //         <strong>${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</strong>
            //     </li>
            // `);
                        //             });

                        // Recent updates
                        const updatesEl = document.getElementById('recent-updates');
                        updatesEl.innerHTML = '';
                        data.recent_updates.forEach(update => {
                            updatesEl.insertAdjacentHTML('beforeend', `
                <li>
                    <span><img src="images/layout_img/msg2.png" class="img-responsive" /></span>
                    <span>
                        <span class="name_user">System</span>
                        <span class="msg_user">${update}</span>
                        <span class="time_ago">Just now</span>
                    </span>
                </li>
            `);
                        });

                    } catch (err) {
                        console.error('Dashboard load failed', err);
                    }
                }


                async function loadApprovals() {
                    try {
                        const res = await fetch("{{ url('/api/superadmin/list-approvals') }}", {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                            }
                        });

                        const data = await res.json();
                        console.log(data);

                        const container = document.getElementById('approvals-list');
                        container.innerHTML = '';

                        data.companies.data.forEach(company => {
                            const createdDate = new Date(company.created_at);
                            const formattedDate = createdDate.toLocaleDateString('en-US', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });

                            const cardHtml = `
                <li>
                    <div class="d-flex flex-column">
                        <strong>${company.name} - Registered on ${formattedDate}</strong>
                        <p>${createdDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                    </div>

                    <button
                        class="btn btn-outline-success approve-company-btn"
                        data-company-id="${company.id}"
                        onclick="approveCompany(this)">
                        Approve
                    </button>
                </li>
            `;

                            container.insertAdjacentHTML('beforeend', cardHtml);
                        });

                    } catch (err) {
                        console.error(err);
                    }
                }

                loadDashboardData();
                loadApprovals();

                async function approveCompany(button) {
                    const companyId = button.dataset.companyId;

                    button.disabled = true;
                    button.innerText = 'Approving...';

                    try {
                        const response = await fetch(`/api/superadmin/approve-company/${companyId}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token')
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw data;
                        }

                        alert(data.message || 'Company approved successfully');

                        // Remove approved item
                        button.closest('li').remove();

                    } catch (error) {
                        console.error(error);
                        alert(error.message || 'Failed to approve company');

                        button.disabled = false;
                        button.innerText = 'Approve';
                    }
                }
            </script>

            {{-- <script>
                const AUDIT_API = '/api/superadmin/audit-logs';
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

                        if (!result.success) {
                            window.showSweetAlert({
                                title: 'Error',
                                message: 'Failed to load audit logs',
                                icon: 'error'
                            });
                            return;
                        }

                        const logs = result.logs.data;
                        const tbody = document.querySelector('#transactions-table tbody');
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
            </script> --}}
            <script>
                const AUDIT_API = '/api/superadmin/audit-logs';
                const token = localStorage.getItem('sanctum_token');

                /* =========================
                   ENTRY POINT
                ========================= */
                document.addEventListener('DOMContentLoaded', () => {
                    loadAuditLogs();
                });

                /* =========================
                   API CALL
                ========================= */
                async function loadAuditLogs(page = 1) {
                    try {
                        const res = await fetch(`${AUDIT_API}?page=${page}`, {
                            headers: {
                                Authorization: `Bearer ${token}`,
                                Accept: 'application/json'
                            }
                        });

                        const result = await res.json();

                        if (!result.success) {
                            showError('Failed to load audit logs');
                            return;
                        }

                        renderAuditTable(result.logs.data);
                        renderPagination(result.logs);

                    } catch (err) {
                        showError(err.message);
                    }
                }

                /* =========================
                   TABLE RENDERING
                ========================= */
                function renderAuditTable(logs) {
                    const tbody = document.querySelector('#audit-table tbody');
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
                        const row = buildAuditRow(log);
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                }

                function buildAuditRow(log) {
                    return `
<tr>
    <td>${log.id}</td>
    <td>
        <span class="badge text-white bg-info">
            ${formatAuditableType(log.auditable_type)}
        </span>
    </td>
    <td>
        <span class="badge text-white bg-${getActionColor(log.action)}">
            ${log.action.toUpperCase()}
        </span>
    </td>
    <td>${log.user?.name ?? 'System'}</td>
    <td>${generateAuditSummary(log)}</td>
    <td>${formatDate(log.created_at)}</td>
</tr>
`;
                }

                /* =========================
                   FORMATTERS
                ========================= */
                function formatAuditableType(type) {
                    const map = {
                        'App\\Models\\Company': 'Company',
                        'App\\Models\\Transaction': 'Transaction',
                        'App\\Models\\User': 'User'
                    };

                    return map[type] ?? 'Unknown';
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

                function formatDate(date) {
                    return new Date(date).toLocaleString();
                }

                /* =========================
                   SUMMARY GENERATOR (KEY)
                ========================= */
                function generateAuditSummary(log) {
                    const type = formatAuditableType(log.auditable_type);
                    const values = log.new_values ?? {};

                    switch (type) {
                        case 'Transaction':
                            return `
                Amount ${values.amount ?? '-'}
                ${values.currency ? values.currency.toUpperCase() : ''}
            `;

                        case 'Company':
                            return `
                Company "${values.name ?? '-'}"
                (${values.email ?? '-'})
            `;

                        case 'User':
                            return `
                User "${values.name ?? values.email ?? '-'}"
            `;

                        default:
                            return `Record #${log.auditable_id}`;
                    }
                }

                /* =========================
                   PAGINATION
                ========================= */
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

                /* =========================
                   ERROR HANDLING
                ========================= */
                function showError(message) {
                    window.showSweetAlert({
                        title: 'Error',
                        message,
                        icon: 'error'
                    });
                }
            </script>
        @endsection
