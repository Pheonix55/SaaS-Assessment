@extends('layouts.app1')
@section('content')
    <div class="midde_cont">
        <div class="container-fluid">
            <div class="row column_title">
                <div class="col-md-12">
                    <div class="page_title">
                        <h2>Profile</h2>
                    </div>
                </div>
            </div>
            <div class="row column1">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="white_shd full margin_bottom_30">
                        <div class="full graph_head">
                            <div class="heading1 margin_0">
                                <h2>User Profile</h2>
                            </div>
                        </div>
                        <div class="full price_table padding_infor_info">
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="full dis_flex center_text">
                                        <div class="profile_img">
                                            <img width="180" class="rounded-circle" id="profile-photo"
                                                src="images/layout_img/user_img.jpg" alt="Profile Photo" />
                                            <input type="file" id="profile-photo-input" class="form-control mt-2 d-none">
                                        </div>
                                        <div class="profile_contant">
                                            <div class="contact_inner">
                                                <h3 id="name-text">-</h3>
                                                <p><strong>About: </strong> <span id="about-text">-</span></p>
                                                <ul class="list-unstyled">
                                                    <li><i class="fa fa-envelope-o"></i> : <span id="email-text">-</span>
                                                    </li>
                                                    <li><i class="fa fa-phone"></i> : <span id="phone-text">-</span></li>
                                                </ul>
                                            </div>
                                            <div class="full inner_elements margin_top_30 ">
                                                <button class="btn btn-primary" id="edit-profile">Edit Profile</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
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

            const profilePhoto = document.getElementById('profile-photo');
            const nameText = document.getElementById('name-text');
            const aboutText = document.getElementById('about-text');
            const emailText = document.getElementById('email-text');
            const phoneText = document.getElementById('phone-text');
            const editBtn = document.getElementById('edit-profile');
            const photoInput = document.getElementById('profile-photo-input');
            // Fetch user profile
            fetch('/api/profile', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.data) {
                        const user = res.data;
                        nameText.innerText = user.name || '-';
                        aboutText.innerText = user.about || '-';
                        emailText.innerText = user.email || '-';
                        phoneText.innerText = user.phone || '-';
                        if (user.profile_image) {
                            profilePhoto.src = '/' + user.profile_image;
                        }
                    }
                })
                .catch(err => console.error(err));

            editBtn.addEventListener('click', function() {
                photoInput.classList.toggle('d-none');

                if (editBtn.dataset.editing === "true") {
                    // Save profile
                    const formData = new FormData();
                    formData.append('name', document.getElementById('name-input').value);
                    formData.append('about', document.getElementById('about-input').value);
                    formData.append('email', document.getElementById('email-input').value);
                    formData.append('phone', document.getElementById('phone-input').value);

                    if (photoInput.files[0]) {
                        formData.append('profile_image', photoInput.files[0]);
                    }

                    fetch('/api/profile/update', {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + token
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                nameText.innerText = formData.get('name');
                                aboutText.innerText = formData.get('about');
                                emailText.innerText = formData.get('email');
                                phoneText.innerText = formData.get('phone');
                                if (res.data.profile_photo) {
                                    profilePhoto.src = '/' + res.data.profile_photo;
                                }
                                editBtn.innerText = 'Edit Profile';
                                editBtn.dataset.editing = "false";
                                alert('Profile updated successfully!');

                                photoInput.classList.toggle('d-none');

                                window.location.reload();

                            } else {
                                alert(res.message || 'Failed to update profile.');
                            }
                        })
                        .catch(err => console.error(err));
                } else {
                    // Switch to edit mode
                    nameText.innerHTML =
                        `<input type="text" class="form-control" id="name-input" value="${nameText.innerText}">`;
                    aboutText.innerHTML =
                        `<input type="text" class="form-control" id="about-input" value="${aboutText.innerText}">`;
                    emailText.innerHTML =
                        `<input type="email" class="form-control" id="email-input" value="${emailText.innerText}">`;
                    phoneText.innerHTML =
                        `<input type="text" class="form-control" id="phone-input" value="${phoneText.innerText}">`;

                    editBtn.innerText = 'Save Profile';
                    editBtn.dataset.editing = "true";
                }
            });
        });
    </script>
@endsection
