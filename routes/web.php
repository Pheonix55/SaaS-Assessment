<?php

use Illuminate\Support\Facades\{Broadcast, Route};
use App\Http\Controllers\Api\{AuthController, InvitationController};
use App\Http\Controllers\PagesController;

Route::post('/api/login', [AuthController::class, 'login'])->middleware('guest')->name('login.post');
Route::get('/', [PagesController::class, 'login'])->name('login');
Route::get('/register', [PagesController::class, 'register'])->name('register');
Route::get('/user/register', [PagesController::class, 'userRegister'])->name('user.register');
Route::get('/super-admin/dashboard', [PagesController::class, 'superAdmindashboard'])->name('superadmin.dashboard');
Route::get('/super-admin/plans', [PagesController::class, 'plansIndex'])->name('superadmin.plans-index');

Route::get('/invite-users', [PagesController::class, 'inviteUser'])->name('invite-user');
Route::get('/admin/dashboard', [PagesController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/user/dashboard', [PagesController::class, 'userDashboard'])->name('user.dashboard');
Route::get('/support', [PagesController::class, 'supportChat'])->name('support.chat');
Route::get('/subscriptions', [PagesController::class, 'newDashboard'])->name('subscriptions');
Route::get('/user/profile', [PagesController::class, 'userProfile'])->name('user.profile');
Route::get('/invite-accept/view/{token}', [InvitationController::class, 'showInviteRegistrationForm'])
    ->name('invite-accept');
Route::get('/test', [PagesController::class, 'test'])->name('test');
Broadcast::routes();
