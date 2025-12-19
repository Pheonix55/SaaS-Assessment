<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'login'])->name('login');
Route::get('/register', [PagesController::class, 'register'])->name('register');

Route::get('/invite-users', [PagesController::class, 'inviteUser'])->name('invite-user');
Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
Route::get('/support', [PagesController::class, 'supportChat'])->name('support.chat');
Route::get('/subscriptions', [PagesController::class, 'newDashboard'])->name('subscriptions');
Route::get('/user/profile', [PagesController::class, 'userProfile'])->name('user.profile');

