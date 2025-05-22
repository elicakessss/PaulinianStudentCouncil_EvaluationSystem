<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizationController as AdminOrganizationController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;

// Adviser Controllers
use App\Http\Controllers\Adviser\DashboardController as AdviserDashboardController;
use App\Http\Controllers\Adviser\OrganizationController as AdviserOrganizationController;
use App\Http\Controllers\Adviser\StudentController as AdviserStudentController;
use App\Http\Controllers\Adviser\EvaluationController as AdviserEvaluationController;
use App\Http\Controllers\Adviser\ReportsController as AdviserReportsController;
use App\Http\Controllers\Adviser\AccountController as AdviserAccountController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\OrganizationController as StudentOrganizationController;
use App\Http\Controllers\Student\EvaluationController as StudentEvaluationController;
use App\Http\Controllers\Student\AccountController as StudentAccountController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'))->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Organization Management
    Route::prefix('organization')->group(function () {
        Route::get('/', [AdminOrganizationController::class, 'index'])->name('admin.organization');
        Route::get('/create', [AdminOrganizationController::class, 'create'])->name('admin.organization.create');
        Route::post('/', [AdminOrganizationController::class, 'store'])->name('admin.organization.store');
        Route::get('/{id}', [AdminOrganizationController::class, 'show'])->name('admin.organization.show');

        Route::get('/evaluation', fn() => view('admin.organization.evaluation'))->name('admin.organization.evaluation');
    });

    // System Management
    Route::prefix('system')->group(function () {
        // User Management
        Route::prefix('admin/users')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('admin.users.index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('admin.users.create');
            Route::post('/', [UserManagementController::class, 'store'])->name('admin.users.store');
            Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
            Route::put('/{id}', [UserManagementController::class, 'update'])->name('admin.users.update');
            Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
            Route::get('/{id}/reassign', [UserManagementController::class, 'showReassignment'])->name('admin.users.reassign');
            Route::post('/{id}/reassign', [UserManagementController::class, 'processReassignment'])->name('admin.users.reassign.process');
        });

        // Other Pages
        Route::get('/organizations', fn() => view('admin.system.organizations.index'))->name('admin.system.organizations.index');
        Route::get('/reports', fn() => view('admin.system.reports.index'))->name('admin.system.reports');
        Route::get('/logs', fn() => view('admin.system.logs.index'))->name('admin.system.logs');
    });

    // Account Management
    Route::get('/account', [AdminAccountController::class, 'index'])->name('admin.account');
    Route::get('/account/edit', [AdminAccountController::class, 'edit'])->name('admin.account.edit');
    Route::put('/account/update', [AdminAccountController::class, 'update'])->name('admin.account.update');
    Route::put('/account/update-password', [AdminAccountController::class, 'updatePassword'])->name('admin.account.update-password');
    Route::post('/account/update-profile-picture', [AdminAccountController::class, 'updateProfilePicture'])->name('admin.account.update-profile-picture');
});

/*
|--------------------------------------------------------------------------
| Adviser Routes
|--------------------------------------------------------------------------
*/
Route::prefix('adviser')->middleware(['auth:adviser'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdviserDashboardController::class, 'index'])->name('adviser.dashboard');

    // Organization Management
    Route::get('/organization', [AdviserOrganizationController::class, 'index'])->name('adviser.organization');

    // Evaluation Management
    Route::get('/evaluation', [AdviserEvaluationController::class, 'index'])->name('adviser.evaluation');

    // Reports
    Route::get('/reports', [AdviserReportsController::class, 'index'])->name('adviser.reports');

    // Student Management
    Route::prefix('students')->group(function () {
        Route::get('/', [AdviserStudentController::class, 'index'])->name('adviser.students');
        Route::get('/create', [AdviserStudentController::class, 'create'])->name('adviser.students.create');
        Route::post('/', [AdviserStudentController::class, 'store'])->name('adviser.students.store');
        Route::get('/{id}/edit', [AdviserStudentController::class, 'edit'])->name('adviser.students.edit');
        Route::put('/{id}', [AdviserStudentController::class, 'update'])->name('adviser.students.update');
        Route::delete('/{id}', [AdviserStudentController::class, 'destroy'])->name('adviser.students.destroy');
    });

    // Account Management
    Route::get('/account', [AdviserAccountController::class, 'index'])->name('adviser.account');
    Route::get('/account/edit', [AdviserAccountController::class, 'edit'])->name('adviser.account.edit');
    Route::put('/account/update', [AdviserAccountController::class, 'update'])->name('adviser.account.update');
    Route::put('/account/update-password', [AdviserAccountController::class, 'updatePassword'])->name('adviser.account.update-password');
    Route::post('/account/update-profile-picture', [AdviserAccountController::class, 'updateProfilePicture'])->name('adviser.account.update-profile-picture');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->middleware(['auth:student'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

    // Organization Management
    Route::get('/organization', [StudentOrganizationController::class, 'index'])->name('student.organization');

    // Evaluation
    Route::get('/evaluation', [StudentEvaluationController::class, 'index'])->name('student.evaluation');

    // Account Management
    Route::get('/account', [StudentAccountController::class, 'index'])->name('student.account');
    Route::get('/account/edit', [StudentAccountController::class, 'edit'])->name('student.account.edit');
    Route::put('/account/update', [StudentAccountController::class, 'update'])->name('student.account.update');
    Route::put('/account/update-password', [StudentAccountController::class, 'updatePassword'])->name('student.account.update-password');
    Route::post('/account/update-profile-picture', [StudentAccountController::class, 'updateProfilePicture'])->name('student.account.update-profile-picture');
});
