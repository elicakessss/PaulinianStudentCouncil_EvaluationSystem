<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\EvaluationReportController;
use App\Http\Controllers\Admin\SystemLogsController;
use App\Http\Controllers\Admin\AccountController;

// Authentication routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit'); 
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // My Organization
    Route::prefix('organization')->group(function () {
        // Organization (with functionality)
        Route::get('/', [OrganizationController::class, 'index'])->name('admin.organization');
        Route::get('/create', [OrganizationController::class, 'create'])->name('admin.organization.create');
        Route::post('/', [OrganizationController::class, 'store'])->name('admin.organization.store');
        Route::get('/{id}', [OrganizationController::class, 'show'])->name('admin.organization.show');

        // Evaluation
        Route::get('/evaluation', function () {
            return view('admin.organization.evaluation');
        })->name('admin.organization.evaluation');
    });

    // System Management
    Route::prefix('system')->group(function () {
        // User Management (with functionality)
        Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create/{role?}', [UserManagementController::class, 'create'])->name('admin.users.create');
        Route::post('/users/{role}', [UserManagementController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{role}/{id}', [UserManagementController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{role}/{id}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{role}/{id}', [UserManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{role}/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

        // Organization Management
        Route::get('/organizations', function() {
            return view('admin.system.organizations.index');
        })->name('admin.system.organizations.index');

        // Evaluation Reports
        Route::get('/reports', function() {
            return view('admin.system.reports.index');
        })->name('admin.system.reports');

        // System Logs
        Route::get('/logs', function() {
            return view('admin.system.logs.index');
        })->name('admin.system.logs');
    });

    // Account
    Route::get('/account', function() {
        return view('admin.account.index');
    })->name('admin.account');
});
