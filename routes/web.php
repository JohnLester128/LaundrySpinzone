<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/customer/choose-branch', [CustomerController::class, 'chooseBranch'])->name('customer.choose-branch');
Route::post('/branch/select', [CustomerController::class, 'selectBranch'])->name('branch.select');

Route::get('/customer/register', [CustomerController::class, 'showRegister'])->name('customer.register');
Route::post('/customer/register', [CustomerController::class, 'register'])->middleware('throttle:10,1')->name('customer.register.store');
Route::get('/customer/login', [CustomerController::class, 'showLogin'])->name('customer.login');
Route::post('/customer/login', [CustomerController::class, 'login'])->middleware('throttle:10,1')->name('customer.login.store');

Route::get('/customer/forgot-password', [CustomerController::class, 'showForgotPassword'])->name('customer.password.request');
Route::post('/customer/forgot-password/verify-user', [CustomerController::class, 'verifyForgotPasswordUser'])->middleware('throttle:10,1')->name('customer.password.verify-user');
Route::post('/customer/forgot-password/reset', [CustomerController::class, 'resetForgotPassword'])->middleware('throttle:10,1')->name('customer.password.reset');

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
    Route::post('/customer/profile/security-question', [CustomerController::class, 'updateSecurityQuestion'])->name('customer.security-question.update');
    Route::post('/customer/profile/branch', [CustomerController::class, 'updateBranch'])->name('customer.branch.update');
    Route::match(['get', 'post'], '/customer/requests/{serviceRequest}/cycle-sync', [CustomerController::class, 'cycleSync'])->name('customer.requests.cycle-sync');

    // Customer Feedback
    Route::get('/customer/feedback', [CustomerController::class, 'feedback'])->name('customer.feedback');
    Route::post('/customer/feedback', [CustomerController::class, 'storeFeedback'])->name('customer.feedback.store');
    Route::post('/customer/feedback/{feedback}/reply', [CustomerController::class, 'replyFeedback'])->name('customer.feedback.reply');
    Route::get('/customer/feedback/{feedback}/replies', [CustomerController::class, 'getFeedbackReplies'])->name('customer.feedback.replies');
    Route::post('/customer/feedback/{feedback}/resolve', [CustomerController::class, 'resolveFeedback'])->name('customer.feedback.resolve');

    // Realtime Notifications Check
    Route::get('/customer/notifications/check', [CustomerController::class, 'checkNotifications'])->name('customer.notifications.check');
});

Route::get('/admin/register', [AdminController::class, 'showRegister'])->name('admin.register');
Route::post('/admin/register', [AdminController::class, 'register'])->middleware('throttle:10,1')->name('admin.register.store');
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->middleware('throttle:10,1')->name('admin.login.store');

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/notifications/check', [AdminController::class, 'checkNotifications'])->name('admin.notifications.check');

    // Feedback
    Route::get('/admin/feedback', [AdminController::class, 'feedback'])->name('admin.feedback');
    Route::post('/admin/feedback/{feedback}/reply', [AdminController::class, 'replyFeedback'])->name('admin.feedback.reply');
    Route::post('/admin/feedback/{feedback}/status', [AdminController::class, 'updateFeedbackStatus'])->name('admin.feedback.status');
    Route::get('/admin/feedback/{feedback}/details', [AdminController::class, 'getFeedbackDetails'])->name('admin.feedback.details');
    Route::delete('/admin/feedback/{feedback}', [AdminController::class, 'destroyFeedback'])->name('admin.feedback.destroy');

    // Service Requests List (Viewing is allowed for both Owner and Manager)
    Route::get('/admin/service-requests', [AdminController::class, 'serviceRequests'])->name('admin.service-requests');

    // Manager Exclusive: Add and Update Service Requests
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/admin/service-requests/create', [AdminController::class, 'createServiceRequest'])->name('admin.service-requests.create');
        Route::post('/admin/service-requests', [AdminController::class, 'storeWalkInServiceRequest'])->name('admin.service-requests.store');
        Route::post('/admin/service-requests/{serviceRequest}/accept', [AdminController::class, 'acceptServiceRequest'])->name('admin.service-requests.accept');
        Route::post('/admin/service-requests/{serviceRequest}/status', [AdminController::class, 'updateServiceRequestStatus'])->name('admin.service-requests.status');
        Route::post('/admin/service-requests/{serviceRequest}/cycle-action', [AdminController::class, 'cycleAction'])->name('admin.service-requests.cycle-action');
        Route::post('/admin/service-requests/{serviceRequest}/details', [AdminController::class, 'updateServiceRequestDetails'])->name('admin.service-requests.details');
    });

    // Service Requests Details & Cycles (Wildcard parameter)
    Route::get('/admin/service-requests/{serviceRequest}', [AdminController::class, 'showServiceRequest'])
        ->whereNumber('serviceRequest')
        ->name('admin.service-requests.show');
    Route::match(['get', 'post'], '/admin/service-requests/{serviceRequest}/cycle-sync', [AdminController::class, 'cycleSync'])
        ->whereNumber('serviceRequest')
        ->name('admin.service-requests.cycle-sync');

    // Machines
    Route::get('/admin/machines', [AdminController::class, 'machines'])->name('admin.machines');
    Route::post('/admin/machines', [AdminController::class, 'storeMachine'])->name('admin.machines.store');
    Route::put('/admin/machines/{machine}', [AdminController::class, 'updateMachine'])->name('admin.machines.update');
    Route::delete('/admin/machines/{machine}', [AdminController::class, 'destroyMachine'])->name('admin.machines.destroy');
    Route::post('/admin/machines/{machine}/toggle-status', [AdminController::class, 'toggleMachineStatus'])->name('admin.machines.toggle-status');

    // Sales Reports
    Route::get('/admin/sales', [AdminController::class, 'sales'])->name('admin.sales');

    // Profile
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Owner (Super Admin) Exclusive Routes
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/login-logs', [AdminController::class, 'loginLogs'])->name('admin.login-logs');
        Route::get('/admin/customers', [AdminController::class, 'customers'])->name('admin.customers');
        Route::post('/admin/customers/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.customers.reset');

        // Branch & Manager Management
        Route::get('/admin/branches', [AdminController::class, 'branches'])->name('admin.branches');
        Route::post('/admin/branches', [AdminController::class, 'storeBranch'])->name('admin.branches.store');
        Route::put('/admin/branches/{branch}', [AdminController::class, 'updateBranchDetails'])->name('admin.branches.update');
        Route::delete('/admin/branches/{branch}', [AdminController::class, 'destroyBranch'])->name('admin.branches.destroy');
        Route::post('/admin/branches/{branch}/toggle-status', [AdminController::class, 'toggleBranchStatus'])->name('admin.branches.toggle-status');
        Route::post('/admin/branches/{branch}/manager', [AdminController::class, 'storeBranchManager'])->name('admin.branches.manager.store');
        Route::post('/admin/branches/managers/{adminUser}/reset-password', [AdminController::class, 'resetManagerPassword'])->name('admin.branches.manager.reset-password');

        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    });
});
