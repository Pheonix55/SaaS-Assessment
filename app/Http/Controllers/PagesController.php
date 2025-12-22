<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function userRegister()
    {
        return view('auth.user-register');
    }

    public function login()
    {
        return view('auth.login');
    }
    public function superAdmindashboard()
    {
        return view('super-admin.dashboard');
    }
    public function plansIndex()
    {
        return view('super-admin.plans.index');
    }

    public function dashboard()
    {
        return view('admin.dashboard1');
    }


    public function userDashboard()
    {
        return view('user.dashboard1');
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
    public function test()
    {
        return view('test');
    }
}
