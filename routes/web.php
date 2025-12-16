<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/',[PagesController::class,'login'])->name('login');
Route::get('/register',[PagesController::class,'register'])->name('register');
Route::get('/dashboard',[PagesController::class,'dashboard'])->name('dashboard');
