<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LateAttendanceController;
use App\Http\Controllers\ExitPermissionController;
use App\Http\Controllers\Admin\ClassManagementController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\LateReasonManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Classes (All authenticated users)
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/{id}', [ClassController::class, 'show'])->name('classes.show');
    
    // Students
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    
    // Late Attendance
    Route::get('/late-attendance', [LateAttendanceController::class, 'index'])->name('late-attendance.index');
    Route::get('/late-attendance/report', [LateAttendanceController::class, 'dailyReport'])->name('late-attendance.report');
    Route::get('/late-attendance/export-pdf', [LateAttendanceController::class, 'exportPDF'])->name('late-attendance.export-pdf');
    Route::get('/late-attendance/create/{studentId}', [LateAttendanceController::class, 'create'])->name('late-attendance.create');
    Route::post('/late-attendance', [LateAttendanceController::class, 'store'])->name('late-attendance.store');
    
    // Multi-student Late Attendance (new dynamic selection feature)
    Route::get('/late-attendance/multi-create', [LateAttendanceController::class, 'multiCreate'])->name('late-attendance.multi-create');
    Route::post('/late-attendance/multi-store', [LateAttendanceController::class, 'multiStore'])->name('late-attendance.multi-store');
    
    // Bulk Late Attendance
    Route::post('/late-attendance/bulk-review', [LateAttendanceController::class, 'bulkReview'])->name('late-attendance.bulk-review');
    Route::post('/late-attendance/bulk-store', [LateAttendanceController::class, 'bulkStore'])->name('late-attendance.bulk-store');
    
    // Admin and Teacher can update status
    Route::middleware(['role:admin,teacher'])->group(function () {
        Route::patch('/late-attendance/{id}/status', [LateAttendanceController::class, 'updateStatus'])->name('late-attendance.update-status');
    });
    
    // Exit Permission Routes
    Route::prefix('exit-permissions')->name('exit-permissions.')->group(function () {
        Route::get('/', [ExitPermissionController::class, 'index'])->name('index');
        Route::get('/classes', [ExitPermissionController::class, 'classList'])->name('classes');
        Route::get('/classes/{classId}', [ExitPermissionController::class, 'showClassExitPermissions'])->name('class-show');
        Route::get('/create', [ExitPermissionController::class, 'create'])->name('create');
        Route::post('/', [ExitPermissionController::class, 'store'])->name('store');
        Route::get('/{exitPermission}', [ExitPermissionController::class, 'show'])->name('show');
        
        // AJAX endpoint to get students by class
        Route::get('/ajax/students-by-class', [ExitPermissionController::class, 'getStudentsByClass'])->name('students-by-class');
        
        // Walas approval (homeroom teacher only)
        Route::middleware(['role:homeroom_teacher'])->group(function () {
            Route::post('/{exitPermission}/walas-approve', [ExitPermissionController::class, 'walasApprove'])->name('walas-approve');
        });
        
        // Admin approval (admin and teacher)
        Route::middleware(['role:admin,teacher'])->group(function () {
            Route::post('/{exitPermission}/admin-approve', [ExitPermissionController::class, 'adminApprove'])->name('admin-approve');
        });
    });
    
    // Admin only routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Class Management
        Route::resource('classes', ClassManagementController::class);
        
        // Student Management
        Route::resource('students', StudentManagementController::class);
        
        // User Management
        Route::resource('users', UserManagementController::class);
        
        // Late Reason Management
        Route::resource('late-reasons', LateReasonManagementController::class);
    });
});

require __DIR__.'/auth.php';
