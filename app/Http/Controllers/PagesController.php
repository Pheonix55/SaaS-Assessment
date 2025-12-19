<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        return view('admin.dashboard1');
    }

    public function inviteUser()
    {
        return view('admin.invitation');
    }

    public function supportChat()
    {
        return view('user.support');
    }

    public function newDashboard()
    {
        return view('admin.subscribtions.plans');
    }

    public function userProfile()
    {
        return view('user.profile');
    }
}
