<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, CompanyController, DashboardController, InvitationController, PlanController, RoleController, StripeProductController, SubscriptionController, SuperAdminController, SupportMessagesController};
use App\Http\Middleware\{CheckCompanyId, CheckRole};

Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'CompanyRegister']);

Route::middleware(['auth:sanctum', CheckCompanyId::class, CheckRole::class.':SUPER_ADMIN'])->prefix('superadmin')->group(function () {
    Route::post('/create/super-admin', [AdminController::class, 'createSuperAdmin']);
    
    Route::get('/dashboard/data', [SuperAdminController::class, 'getDashboardData']);

    Route::get('/approve-company/{id}', [SuperAdminController::class, 'approveCompany']);
    Route::get('/list-approvals', [SuperAdminController::class, 'listApprovals']);

    // manage-plans
    Route::get('stripe/products', [StripeProductController::class, 'index']);
    Route::post('stripe/products', [StripeProductController::class, 'store']);
    Route::put('stripe/products/{plan}', [StripeProductController::class, 'update']);
    Route::delete('stripe/products/{plan}', [StripeProductController::class, 'destroy']);

});

Route::middleware(['auth:sanctum', CheckCompanyId::class, CheckRole::class.':admin'])->group(function () {

    Route::get('/users', [AuthController::class, 'listUser']);
    Route::post('/assign-role', [RoleController::class, 'assignRole']);
    Route::get('/check-role/{id}', [RoleController::class, 'checkRole']);

    // admin only routes
    Route::get('/admin/users', [CompanyController::class, 'getAllUsers']);

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
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/me', [AuthController::class, 'getAuthUser']);
    Route::get('/admin/getSubscriptionInfo/{id}', [SubscriptionController::class, 'getSubscriptionInfo']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/admin/plans/{id}', [SubscriptionController::class, 'cancelSubscription']);
    Route::get('/admin/getActiveSubscriptionInfo/{id}', [SubscriptionController::class, 'getActiveSubscriptionInfo']);
    Route::get('/get/plans/subscriptions', [DashboardController::class, 'getPlansSubscriptions']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// User routes
Route::middleware(['auth:sanctum', CheckCompanyId::class])->group(function () {
    Route::get('/list/roles', [RoleController::class, 'listRoles']);

    Route::post('/support/threads', [SupportMessagesController::class, 'supportThread']);
    Route::get('/support/threads', [SupportMessagesController::class, 'userThreads']);
    Route::get('/support/threads/{thread}', [SupportMessagesController::class, 'getMessagesForUser']);
    Route::post('/support/reply/{thread}', [SupportMessagesController::class, 'supportReply']);
    Route::post('/support/threads/{thread}/close', [SupportMessagesController::class, 'closeThread']);

    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('/profile', [AuthController::class, 'GetUserProfile']);

});

Route::post('/invite-accept/{token}', [InvitationController::class, 'registerAndAcceptInvite'])
    ->name('invite-accept.submit');
Route::get('/invoices/{invoiceId}/download/{plan_id}', [SubscriptionController::class, 'download'])
    ->name('download.invoice');
Route::middleware([
    'auth:sanctum', CheckCompanyId::class,
])->get('/test/check-role/{role}', function ($role) {
    return response()->json([
        'has_role' => auth()->user()->hasRole($role),
    ]);
});
