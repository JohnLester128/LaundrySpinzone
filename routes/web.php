<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/customer/choose-branch', [CustomerController::class, 'chooseBranch'])->name('customer.choose-branch');
Route::post('/branch/select', [CustomerController::class, 'selectBranch'])->name('branch.select');

Route::get('/customer/register', [CustomerController::class, 'showRegister'])->name('customer.register');
Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register.store');
Route::get('/customer/login', [CustomerController::class, 'showLogin'])->name('customer.login');
Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login.store');

Route::middleware('auth:customer')->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/customer/requests', [CustomerController::class, 'requests'])->name('customer.requests');
    Route::get('/customer/requests/{serviceRequest}', [CustomerController::class, 'showRequest'])->name('customer.requests.show');
    Route::get('/customer/about', [CustomerController::class, 'about'])->name('customer.about');
    Route::post('/customer/logout', [CustomerController::class, 'logout'])->name('customer.logout');
    Route::get('/customer/service-requests/create', [CustomerController::class, 'createServiceRequest'])->name('customer.service-requests.create');
    Route::get('/customer/service-requests', [CustomerController::class, 'createServiceRequest'])->name('customer.service-requests');
    Route::post('/customer/service-requests', [CustomerController::class, 'storeServiceRequest'])->name('customer.service-requests.store');
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::post('/customer/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::post('/customer/profile/password', [CustomerController::class, 'updatePassword'])->name('customer.password.update');
    Route::post('/customer/profile/branch', [CustomerController::class, 'updateBranch'])->name('customer.branch.update');
});

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
    Route::get('/admin/service-requests', [AdminController::class, 'serviceRequests'])->name('admin.service-requests');
    Route::get('/admin/service-requests/create', [AdminController::class, 'createServiceRequest'])->name('admin.service-requests.create');
    Route::post('/admin/service-requests', [AdminController::class, 'storeWalkInServiceRequest'])->name('admin.service-requests.store');
    Route::get('/admin/service-requests/{serviceRequest}', [AdminController::class, 'showServiceRequest'])->name('admin.service-requests.show');
    Route::post('/admin/service-requests/{serviceRequest}/accept', [AdminController::class, 'acceptServiceRequest'])->name('admin.service-requests.accept');
    Route::post('/admin/service-requests/{serviceRequest}/status', [AdminController::class, 'updateServiceRequestStatus'])->name('admin.service-requests.status');
    Route::post('/admin/service-requests/{serviceRequest}/details', [AdminController::class, 'updateServiceRequestDetails'])->name('admin.service-requests.details');
    Route::get('/admin/machines', [AdminController::class, 'machines'])->name('admin.machines');
    Route::post('/admin/machines', [AdminController::class, 'storeMachine'])->name('admin.machines.store');
    Route::put('/admin/machines/{machine}', [AdminController::class, 'updateMachine'])->name('admin.machines.update');
    Route::delete('/admin/machines/{machine}', [AdminController::class, 'destroyMachine'])->name('admin.machines.destroy');
    Route::post('/admin/machines/{machine}/toggle-status', [AdminController::class, 'toggleMachineStatus'])->name('admin.machines.toggle-status');
    Route::get('/admin/sales', [AdminController::class, 'sales'])->name('admin.sales');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});
