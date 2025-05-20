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
        });

        // Other Pages
        Route::get('/organizations', fn() => view('admin.system.organizations.index'))->name('admin.system.organizations.index');
        Route::get('/reports', fn() => view('admin.system.reports.index'))->name('admin.system.reports');
        Route::get('/logs', fn() => view('admin.system.logs.index'))->name('admin.system.logs');
    });

    // Account
    Route::get('/account', fn() => view('admin.account.index'))->name('admin.account');
});

/*
|--------------------------------------------------------------------------
| Adviser Routes
|--------------------------------------------------------------------------
*/
Route::prefix('adviser')->middleware('auth:adviser')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Adviser\DashboardController::class, 'index'])->name('adviser.dashboard');
    Route::get('/organization', [App\Http\Controllers\Adviser\OrganizationController::class, 'index'])->name('adviser.organization');
    Route::get('/students', [App\Http\Controllers\Adviser\StudentController::class, 'index'])->name('adviser.students');
    Route::get('/evaluation', [App\Http\Controllers\Adviser\EvaluationController::class, 'index'])->name('adviser.evaluation');
    Route::get('/reports', [App\Http\Controllers\Adviser\ReportsController::class, 'index'])->name('adviser.reports');
    Route::get('/account', [App\Http\Controllers\Adviser\AccountController::class, 'index'])->name('adviser.account');
});


// Inside the adviser prefix group
Route::prefix('students')->group(function () {
    Route::get('/', [App\Http\Controllers\Adviser\StudentController::class, 'index'])->name('adviser.students');
    Route::get('/create', [App\Http\Controllers\Adviser\StudentController::class, 'create'])->name('adviser.students.create');
    Route::post('/', [App\Http\Controllers\Adviser\StudentController::class, 'store'])->name('adviser.students.store');
    Route::get('/{id}/edit', [App\Http\Controllers\Adviser\StudentController::class, 'edit'])->name('adviser.students.edit');
    Route::put('/{id}', [App\Http\Controllers\Adviser\StudentController::class, 'update'])->name('adviser.students.update');
    Route::delete('/{id}', [App\Http\Controllers\Adviser\StudentController::class, 'destroy'])->name('adviser.students.destroy');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->middleware('auth:student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/organization', [StudentOrganizationController::class, 'index'])->name('student.organization');
    Route::get('/evaluation', [StudentEvaluationController::class, 'index'])->name('student.evaluation');
});
