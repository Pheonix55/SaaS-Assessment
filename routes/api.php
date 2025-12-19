<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SupportMessagesController;
use App\Http\Middleware\CheckCompanyId;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'CompanyRegister']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', CheckCompanyId::class, CheckRole::class.':admin'])->group(function () {

    Route::post('/create/super-admin', [AdminController::class, 'createSuperAdmin']);

});

Route::middleware(['auth:sanctum', CheckCompanyId::class, CheckRole::class.':admin'])->group(function () {

    Route::get('/users', [AuthController::class, 'listUser']);
    Route::post('/assign-role', [RoleController::class, 'assignRole']);
    Route::get('/check-role/{id}', [RoleController::class, 'checkRole']);

    // admin only routes
    Route::get('/admin/users', [CompanyController::class, 'getAllUsers']);
    Route::get('/admin/getSubscriptionInfo/{id}', [SubscriptionController::class, 'getSubscriptionInfo']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/admin/plans/{id}', [SubscriptionController::class, 'cancelSubscription']);
    Route::get('/admin/getActiveSubscriptionInfo/{id}', [SubscriptionController::class, 'getActiveSubscriptionInfo']);

    // plan crud routes
    Route::get('/admin/plans', [PlanController::class, 'getAllPlans']);
    Route::post('/admin/plans/create', [PlanController::class, 'store']);
    Route::post('/admin/plans/delete/{id}', [PlanController::class, 'delete']);

    // dashboard

    Route::post('/invite-user', [InvitationController::class, 'invite']);

    Route::get('/admin/get-invitations', [InvitationController::class, 'listInvitationsForAdmin']);
    Route::get('/admin/get-transactions', [SubscriptionController::class, 'getTransactions']);

    Route::get('/subscription/events', [SubscriptionController::class, 'getSubscriptionEvents']);
});
// support routes
Route::middleware(['auth:sanctum', CheckCompanyId::class])->group(function () {});
// User routes
Route::middleware(['auth:sanctum', CheckCompanyId::class])->group(function () {
    Route::get('/get/plans/subscriptions', [DashboardController::class, 'getPlansSubscriptions']);
    Route::get('/list/roles', [RoleController::class, 'listRoles']);

    Route::post('/support/threads', [SupportMessagesController::class, 'supportThread']);
    Route::get('/support/threads', [SupportMessagesController::class, 'userThreads']);
    Route::get('/support/threads/{thread}', [SupportMessagesController::class, 'getMessagesForUser']);
    Route::post('/support/reply/{thread}', [SupportMessagesController::class, 'supportReply']);
    Route::post('/support/threads/{thread}/close', [SupportMessagesController::class, 'closeThread']);

    Route::get('/me', [AuthController::class, 'getAuthUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('/profile', [AuthController::class, 'GetUserProfile']);

});

Route::get('/invite-accept/{token}', [InvitationController::class, 'acceptInvite'])->name('invite-accept');
Route::get('/invoices/{invoiceId}/download/{plan_id}', [SubscriptionController::class, 'download'])
    ->name('download.invoice');
