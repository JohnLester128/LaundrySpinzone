<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\CustomerController;

Route::get('/customer/register', [CustomerController::class, 'showRegister'])->name('customer.register');
Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register.store');
Route::get('/customer/login', [CustomerController::class, 'showLogin'])->name('customer.login');
Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login.store');

Route::middleware('auth:customer')->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/customer/logout', [CustomerController::class, 'logout'])->name('customer.logout');
});

use App\Http\Controllers\AdminController;

Route::get('/admin/register', [AdminController::class, 'showRegister'])->name('admin.register');
Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register.store');
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.store');

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/login-logs', [AdminController::class, 'loginLogs'])->name('admin.login-logs');
    Route::get('/admin/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::post('/admin/customers/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.customers.reset');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
});
// Inside auth:customer group
Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
Route::post('/customer/profile/password', [CustomerController::class, 'updatePassword'])->name('customer.password.update');

// Inside auth:admin group
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
Route::post('/admin/profile/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');