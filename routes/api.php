<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, CompanyController, PlanController, RoleController, SubscriptionController};

Route::post('/user/register',[AuthController::class,'register']);
Route::post('/register',[AuthController::class,'CompanyRegister']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [AuthController::class, 'listUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/assign-role',[RoleController::class,'assignRole']);
    Route::get('/check-role/{id}',[RoleController::class,'checkRole']);

    // admin only routes
    Route::get('/admin/users', [CompanyController::class, 'getAllUsers']);
    Route::get('/admin/getSubscriptionInfo/{id}', [SubscriptionController::class, 'getSubscriptionInfo']);
    Route::get('/admin/getActiveSubscriptionInfo/{id}', [SubscriptionController::class, 'getActiveSubscriptionInfo']);
    Route::post('/subscribe',[SubscriptionController::class,'subscribe']);
    Route::get('/admin/plans/{id}', [SubscriptionController::class, 'cancelSubscription']);

    // plan crud routes 
    Route::get('/admin/plans', [PlanController::class, 'getAllPlans']);
    Route::post('/admin/plans/create',[PlanController::class,'store']);
    Route::post('/admin/plans/delete/{id}',[PlanController::class,'delete']);
});

