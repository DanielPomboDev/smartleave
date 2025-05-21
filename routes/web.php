<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Set root to redirect to login page
Route::get('/', function () {
    return redirect()->route('login.show');
});

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout-get', [AuthController::class, 'logout'])->name('logout.get');

// Dashboard and HR Manager routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/employee-dashboard', function () {
        return view('employee_dashboard');
    })->name('employee.dashboard');

    Route::get('/hr-dashboard', function () {
        // TODO: Create this view or point to a controller method
        return view('hr_dashboard'); 
    })->name('hr.dashboard');

    Route::get('/leave-requests', function () {
        return view('hr_leave_requests');
    })->name('leave.requests');

    Route::get('/leave-credits', function () {
        return view('hr_leave_credits');
    })->name('leave.credits');

    Route::get('/hr-employees', function () {
        return view('hr_employees');
    })->name('hr.employees');

    Route::get('/hr-profile', function () {
        return view('hr_profile');
    })->name('hr.profile');

    Route::get('/hr-settings', function () {
        return view('hr_settings');
    })->name('hr.settings');
});

// Department Admin routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/department-leave-requests', function () {
        return view('department_leave_requests');
    })->name('department.leave.requests');

    Route::get('/department-profile', function () {
        return view('department_profile');
    })->name('department.profile');

    Route::get('/department-settings', function () {
        return view('department_settings');
    })->name('department.settings');
});

// Employee routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/request-leave', function () {
        return view('employee_request_leave');
    })->name('employee.request.leave');

    Route::get('/settings', function () {
        $user = Auth::user();
        return view('employee_settings', ['user' => $user]);
    })->name('employee.settings');

    Route::get('/profile', function () {
        return view('employee_profile');
    })->name('employee.profile');

    Route::get('/leave-history', [LeaveController::class, 'history'])->name('employee.leave.history');

    Route::put('/settings', function (Illuminate\Http\Request $request) {
        // TODO: Implement settings update logic here
        // For now, just redirect back with a success message
        return back()->with('success', 'Settings updated successfully!'); 
    })->name('settings.update');

    Route::put('/settings/notifications', function (Illuminate\Http\Request $request) {
        // TODO: Implement notification settings update logic here
        return back()->with('success', 'Notification settings updated successfully!');
    })->name('settings.notifications.update');
});

// Leave request routes
Route::get('/view-leave-request/{id}', function () {
    return view('view_leave_request');
})->name('leave.view');

// Leave management routes
Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
Route::put('/leave/{id}', [LeaveController::class, 'update'])->name('leave.update');
Route::delete('/leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
Route::post('/leave/{id}/process', [LeaveController::class, 'processApproval'])->name('leave.process');
