<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

// Admin Controllers - My Organization
use App\Http\Controllers\Admin\MyOrganization\OrganizationManagementController as AdminMyOrganizationController;
use App\Http\Controllers\Admin\MyOrganization\EvaluationController as AdminMyOrganizationEvaluationController;

// Admin Controllers - System Management
use App\Http\Controllers\Admin\SystemManagement\UserManagementController;
use App\Http\Controllers\Admin\SystemManagement\OrganizationController as AdminSystemOrganizationController;
use App\Http\Controllers\Admin\SystemManagement\EvaluationReportController as AdminSystemEvaluationReportController;
use App\Http\Controllers\Admin\SystemManagement\SystemLogsController as AdminSystemLogsController;

// Admin Controllers - General
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;

// Adviser Controllers - My Organization
use App\Http\Controllers\Adviser\MyOrganization\OrganizationManagementController as AdviserMyOrganizationController;
use App\Http\Controllers\Adviser\MyOrganization\EvaluationController as AdviserMyOrganizationEvaluationController;
use App\Http\Controllers\Adviser\MyOrganization\StudentManagementController as AdviserMyOrganizationStudentController;

// Adviser Controllers - General
use App\Http\Controllers\Adviser\DashboardController as AdviserDashboardController;
use App\Http\Controllers\Adviser\AccountController as AdviserAccountController;

// Student Controllers - My Organization
use App\Http\Controllers\Student\MyOrganization\OrganizationController as StudentMyOrganizationController;
use App\Http\Controllers\Student\MyOrganization\EvaluationController as StudentMyOrganizationEvaluationController;

// Student Controllers - General
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
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

    // TEMPORARY REDIRECTS (Remove these after updating all references)
    Route::get('/organization', function() {
        return redirect()->route('admin.my_organization.organization_management.index');
    })->name('admin.organization');

    Route::get('/organization/create', function() {
        return redirect()->route('admin.my_organization.organization_management.create');
    })->name('admin.organization.create');

    Route::post('/organization', function() {
        return redirect()->route('admin.my_organization.organization_management.store');
    })->name('admin.organization.store');

    Route::get('/organization/{id}', function($id) {
        return redirect()->route('admin.my_organization.organization_management.show', $id);
    })->name('admin.organization.show');

    Route::get('/organization/{id}/edit', function($id) {
        return redirect()->route('admin.my_organization.organization_management.edit', $id);
    })->name('admin.organization.edit');

    Route::put('/organization/{id}', function($id) {
        return redirect()->route('admin.my_organization.organization_management.update', $id);
    })->name('admin.organization.update');

    Route::delete('/organization/{id}', function($id) {
        return redirect()->route('admin.my_organization.organization_management.destroy', $id);
    })->name('admin.organization.destroy');

    Route::get('/organization/evaluation', function() {
        return redirect()->route('admin.my_organization.evaluation.index');
    })->name('admin.organization.evaluation');

    Route::get('/users', function() {
        return redirect()->route('admin.system_management.user_management.index');
    })->name('admin.users.index');

    Route::get('/users/create', function() {
        return redirect()->route('admin.system_management.user_management.create');
    })->name('admin.users.create');

    Route::post('/users', function() {
        return redirect()->route('admin.system_management.user_management.store');
    })->name('admin.users.store');

    Route::get('/users/{id}/edit', function($id) {
        return redirect()->route('admin.system_management.user_management.edit', $id);
    })->name('admin.users.edit');

    Route::put('/users/{id}', function($id) {
        return redirect()->route('admin.system_management.user_management.update', $id);
    })->name('admin.users.update');

    Route::delete('/users/{id}', function($id) {
        return redirect()->route('admin.system_management.user_management.destroy', $id);
    })->name('admin.users.destroy');

    Route::get('/users/{id}/reassign', function($id) {
        return redirect()->route('admin.system_management.user_management.reassign', $id);
    })->name('admin.users.reassign');

    Route::post('/users/{id}/reassign', function($id) {
        return redirect()->route('admin.system_management.user_management.reassign_process', $id);
    })->name('admin.users.reassign.process');

    Route::get('/system/organizations', function() {
        return redirect()->route('admin.system_management.organizations.index');
    })->name('admin.system.organizations.index');

    Route::get('/system/reports', function() {
        return redirect()->route('admin.system_management.evaluation_report.index');
    })->name('admin.system.reports');

    Route::get('/system/logs', function() {
        return redirect()->route('admin.system_management.system_logs.index');
    })->name('admin.system.logs');

    // MY ORGANIZATION SECTION
    Route::prefix('my-organization')->group(function () {
        // Organization Management (Admin's University-Wide Councils)
        Route::prefix('organization-management')->group(function () {
            Route::get('/', [AdminMyOrganizationController::class, 'index'])->name('admin.my_organization.organization_management.index');
            Route::get('/create', [AdminMyOrganizationController::class, 'create'])->name('admin.my_organization.organization_management.create');
            Route::post('/', [AdminMyOrganizationController::class, 'store'])->name('admin.my_organization.organization_management.store');
            Route::get('/{id}', [AdminMyOrganizationController::class, 'show'])->name('admin.my_organization.organization_management.show');
            Route::get('/{id}/edit', [AdminMyOrganizationController::class, 'edit'])->name('admin.my_organization.organization_management.edit');
            Route::put('/{id}', [AdminMyOrganizationController::class, 'update'])->name('admin.my_organization.organization_management.update');
            Route::delete('/{id}', [AdminMyOrganizationController::class, 'destroy'])->name('admin.my_organization.organization_management.destroy');
            Route::post('/{id}/assign-student', [AdminMyOrganizationController::class, 'assignStudent'])->name('admin.my_organization.organization_management.assign_student');
            Route::delete('/{councilId}/remove-student/{positionId}', [AdminMyOrganizationController::class, 'removeStudent'])->name('admin.my_organization.organization_management.remove_student');
        });

        // Evaluation (Admin's Council Evaluations)
        Route::prefix('evaluation')->group(function () {
            Route::get('/', [AdminMyOrganizationEvaluationController::class, 'index'])->name('admin.my_organization.evaluation.index');
        });
    });

    // SYSTEM MANAGEMENT SECTION
    Route::prefix('system-management')->group(function () {
        // User Management
        Route::prefix('user-management')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('admin.system_management.user_management.index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('admin.system_management.user_management.create');
            Route::post('/', [UserManagementController::class, 'store'])->name('admin.system_management.user_management.store');
            Route::get('/{id}/show', [UserManagementController::class, 'show'])->name('admin.system_management.user_management.show');
            Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('admin.system_management.user_management.edit');
            Route::put('/{id}', [UserManagementController::class, 'update'])->name('admin.system_management.user_management.update');
            Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('admin.system_management.user_management.destroy');
            Route::get('/{id}/reassign', [UserManagementController::class, 'showReassignment'])->name('admin.system_management.user_management.reassign');
            Route::post('/{id}/reassign', [UserManagementController::class, 'processReassignment'])->name('admin.system_management.user_management.reassign_process');
        });

        // Organizations Management (System-wide oversight)
        Route::prefix('organizations')->group(function () {
            Route::get('/', [AdminSystemOrganizationController::class, 'index'])->name('admin.system_management.organizations.index');
            Route::get('/{id}', [AdminSystemOrganizationController::class, 'show'])->name('admin.system_management.organizations.show');
            Route::post('/{id}/activate', [AdminSystemOrganizationController::class, 'activate'])->name('admin.system_management.organizations.activate');
            Route::delete('/{id}', [AdminSystemOrganizationController::class, 'destroy'])->name('admin.system_management.organizations.destroy');
        });

        // Evaluation Reports
        Route::prefix('evaluation-report')->group(function () {
            Route::get('/', [AdminSystemEvaluationReportController::class, 'index'])->name('admin.system_management.evaluation_report.index');
        });

        // System Logs
        Route::prefix('system-logs')->group(function () {
            Route::get('/', [AdminSystemLogsController::class, 'index'])->name('admin.system_management.system_logs.index');
        });
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

    // TEMPORARY REDIRECTS (Remove these after updating all references)
    Route::get('/organization', function() {
        return redirect()->route('adviser.my_organization.organization_management.index');
    })->name('adviser.organization');

    Route::get('/organization/create', function() {
        return redirect()->route('adviser.my_organization.organization_management.create');
    })->name('adviser.organization.create');

    Route::post('/organization', function() {
        return redirect()->route('adviser.my_organization.organization_management.store');
    })->name('adviser.organization.store');

    Route::get('/organization/{id}', function($id) {
        return redirect()->route('adviser.my_organization.organization_management.show', $id);
    })->name('adviser.organization.show');

    Route::get('/organization/{id}/edit', function($id) {
        return redirect()->route('adviser.my_organization.organization_management.edit', $id);
    })->name('adviser.organization.edit');

    Route::put('/organization/{id}', function($id) {
        return redirect()->route('adviser.my_organization.organization_management.update', $id);
    })->name('adviser.organization.update');

    Route::delete('/organization/{id}', function($id) {
        return redirect()->route('adviser.my_organization.organization_management.destroy', $id);
    })->name('adviser.organization.destroy');

    Route::post('/organization/{id}/assign-student', function($id) {
        return redirect()->route('adviser.my_organization.organization_management.assign_student', $id);
    })->name('adviser.organization.assign_student');

    Route::delete('/organization/{councilId}/remove-student/{positionId}', function($councilId, $positionId) {
        return redirect()->route('adviser.my_organization.organization_management.remove_student', [$councilId, $positionId]);
    })->name('adviser.organization.remove_student');

    Route::get('/organization/api/students', function() {
        return redirect()->route('adviser.my_organization.organization_management.api.students');
    })->name('adviser.organization.api.students');

    Route::get('/evaluation', function() {
        return redirect()->route('adviser.my_organization.evaluation.index');
    })->name('adviser.evaluation');

    Route::get('/students', function() {
        return redirect()->route('adviser.my_organization.student_management.index');
    })->name('adviser.students');

    Route::get('/students/create', function() {
        return redirect()->route('adviser.my_organization.student_management.create');
    })->name('adviser.students.create');

    Route::post('/students', function() {
        return redirect()->route('adviser.my_organization.student_management.store');
    })->name('adviser.students.store');

    Route::get('/students/{id}/edit', function($id) {
        return redirect()->route('adviser.my_organization.student_management.edit', $id);
    })->name('adviser.students.edit');

    Route::put('/students/{id}', function($id) {
        return redirect()->route('adviser.my_organization.student_management.update', $id);
    })->name('adviser.students.update');

    Route::delete('/students/{id}', function($id) {
        return redirect()->route('adviser.my_organization.student_management.destroy', $id);
    })->name('adviser.students.destroy');

    Route::get('/reports', function() {
        return view('adviser.placeholder', ['title' => 'Reports', 'message' => 'Reports feature will be implemented in a future phase.']);
    })->name('adviser.reports');

    // MY ORGANIZATION SECTION
    Route::prefix('my-organization')->group(function () {
        // Organization Management (Adviser's Department Councils)
        Route::prefix('organization-management')->group(function () {
            Route::get('/', [AdviserMyOrganizationController::class, 'index'])->name('adviser.my_organization.organization_management.index');
            Route::get('/create', [AdviserMyOrganizationController::class, 'create'])->name('adviser.my_organization.organization_management.create');
            Route::post('/', [AdviserMyOrganizationController::class, 'store'])->name('adviser.my_organization.organization_management.store');
            Route::get('/{id}', [AdviserMyOrganizationController::class, 'show'])->name('adviser.my_organization.organization_management.show');
            Route::get('/{id}/edit', [AdviserMyOrganizationController::class, 'edit'])->name('adviser.my_organization.organization_management.edit');
            Route::put('/{id}', [AdviserMyOrganizationController::class, 'update'])->name('adviser.my_organization.organization_management.update');
            Route::delete('/{id}', [AdviserMyOrganizationController::class, 'destroy'])->name('adviser.my_organization.organization_management.destroy');
            Route::post('/{id}/assign-student', [AdviserMyOrganizationController::class, 'assignStudent'])->name('adviser.my_organization.organization_management.assign_student');
            Route::delete('/{councilId}/remove-student/{positionId}', [AdviserMyOrganizationController::class, 'removeStudent'])->name('adviser.my_organization.organization_management.remove_student');
            Route::get('/api/students', [AdviserMyOrganizationController::class, 'getAvailableStudents'])->name('adviser.my_organization.organization_management.api.students');
        });

        // Evaluation (Adviser's Council Evaluations)
        Route::prefix('evaluation')->group(function () {
            Route::get('/', [AdviserMyOrganizationEvaluationController::class, 'index'])->name('adviser.my_organization.evaluation.index');
        });

        // Student Management
        Route::prefix('student-management')->group(function () {
            Route::get('/', [AdviserMyOrganizationStudentController::class, 'index'])->name('adviser.my_organization.student_management.index');
            Route::get('/create', [AdviserMyOrganizationStudentController::class, 'create'])->name('adviser.my_organization.student_management.create');
            Route::post('/', [AdviserMyOrganizationStudentController::class, 'store'])->name('adviser.my_organization.student_management.store');
            Route::get('/{id}/show', [AdviserMyOrganizationStudentController::class, 'show'])->name('adviser.my_organization.student_management.show');
            Route::get('/{id}/edit', [AdviserMyOrganizationStudentController::class, 'edit'])->name('adviser.my_organization.student_management.edit');
            Route::put('/{id}', [AdviserMyOrganizationStudentController::class, 'update'])->name('adviser.my_organization.student_management.update');
            Route::delete('/{id}', [AdviserMyOrganizationStudentController::class, 'destroy'])->name('adviser.my_organization.student_management.destroy');
        });
    });

    // Account Management
    Route::get('/account', [AdviserAccountController::class, 'index'])->name('adviser.account.index');
    Route::get('/account/edit', [AdviserAccountController::class, 'edit'])->name('adviser.account.edit');
    Route::put('/account/update', [AdviserAccountController::class, 'update'])->name('adviser.account.update');
    Route::put('/account/update-password', [AdviserAccountController::class, 'updatePassword'])->name('adviser.account.update_password');
    Route::post('/account/update-profile-picture', [AdviserAccountController::class, 'updateProfilePicture'])->name('adviser.account.update_profile_picture');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->middleware(['auth:student'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

    // TEMPORARY REDIRECTS (Remove these after updating all references)
    Route::get('/organization', function() {
        return redirect()->route('student.my_organization.organization.index');
    })->name('student.organization');

    Route::get('/evaluation', function() {
        return redirect()->route('student.my_organization.evaluation.index');
    })->name('student.evaluation');

    // MY ORGANIZATION SECTION
    Route::prefix('my-organization')->group(function () {
        // Organization (Student's Council Memberships)
        Route::prefix('organization')->group(function () {
            Route::get('/', [StudentMyOrganizationController::class, 'index'])->name('student.my_organization.organization.index');
        });

        // Evaluation (Student's Council Evaluations)
        Route::prefix('evaluation')->group(function () {
            Route::get('/', [StudentMyOrganizationEvaluationController::class, 'index'])->name('student.my_organization.evaluation.index');
        });
    });

    // Account Management
    Route::get('/account', [StudentAccountController::class, 'index'])->name('student.account.index');
    Route::get('/account/edit', [StudentAccountController::class, 'edit'])->name('student.account.edit');
    Route::put('/account/update', [StudentAccountController::class, 'update'])->name('student.account.update');
    Route::put('/account/update-password', [StudentAccountController::class, 'updatePassword'])->name('student.account.update_password');
    Route::post('/account/update-profile-picture', [StudentAccountController::class, 'updateProfilePicture'])->name('student.account.update_profile_picture');
});
