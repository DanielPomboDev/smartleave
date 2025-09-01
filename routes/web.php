<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HRController;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;

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

    Route::get('/leave-approve/{id}', [LeaveController::class, 'showHRApproval'])->name('leave.approve.start');
    Route::post('/leave-approve/{id}/process', [LeaveController::class, 'processHRApproval'])->name('leave.approve.process');

    Route::get('/leave-records', [HRController::class, 'leaveRecords'])->name('leave.records');
    Route::get('/leave-record/{id}', [HRController::class, 'showLeaveRecord'])->name('leave.record.show');

    Route::get('/hr-employees', [HRController::class, 'employees'])->name('hr.employees');
    Route::post('/hr-employees', [HRController::class, 'store'])->name('hr.employees.store');

    Route::get('/hr-profile', function () {
        return view('hr_profile');
    })->name('hr.profile');

    Route::get('/hr-settings', function () {
        return view('hr_settings');
    })->name('hr.settings');
});

// Department Admin routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/department-dashboard', [LeaveController::class, 'departmentDashboard'])->name('department.dashboard');

    Route::get('/department-leave-requests', [LeaveController::class, 'departmentLeaveRequests'])->name('department.leave.requests');

    Route::get('/department-leave-approve/{id}', [LeaveController::class, 'showDepartmentApproval'])->name('department.leave.approve.start');

    Route::post('/department-leave-approve/{id}/process', [LeaveController::class, 'processDepartmentApproval'])->name('department.leave.approve.process');

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

    Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    Route::put('/settings/notifications', function (Illuminate\Http\Request $request) {
        // TODO: Implement notification settings update logic here
        return back()->with('success', 'Notification settings updated successfully!');
    })->name('settings.notifications.update');
});

// Leave request routes
Route::get('/view-leave-request/{id}', function ($id) {
    $leaveRequest = LeaveRequest::findOrFail($id);
    return view('view_leave_request', compact('leaveRequest'));
})->name('leave.view');

// Leave management routes
Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
Route::put('/leave/{id}', [LeaveController::class, 'update'])->name('leave.update');
Route::delete('/leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
Route::post('/leave/{id}/process', [LeaveController::class, 'processApproval'])->name('leave.process');

// HR Manager routes - require authentication
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [HRController::class, 'dashboard'])->name('dashboard');

    Route::get('/employees', [HRController::class, 'employees'])->name('employees');
    Route::post('/employees', [HRController::class, 'store'])->name('employees.store');
    Route::get('/employees/{id}/edit', [HRController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}', [HRController::class, 'update'])->name('employees.update');

    Route::get('/profile', function () {
        if (Auth::user()->user_type !== 'hr') {
            return redirect('/employee-dashboard');
        }
        return view('hr_profile');
    })->name('profile');

    Route::get('/settings', function () {
        if (Auth::user()->user_type !== 'hr') {
            return redirect('/employee-dashboard');
        }
        return view('hr_settings');
    })->name('settings');

    Route::get('/leave-requests', [HRController::class, 'leaveRequests'])->name('leave.requests');

});

// Mayor routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/mayor-dashboard', [LeaveController::class, 'mayorDashboard'])->name('mayor.dashboard');
    Route::get('/mayor-leave-requests', [LeaveController::class, 'mayorLeaveRequests'])->name('mayor.leave.requests');
    Route::get('/mayor-leave-approve/{id}', [LeaveController::class, 'showMayorApproval'])->name('mayor.leave.approve.start');
    Route::post('/mayor-leave-approve/{id}/process', [LeaveController::class, 'processMayorApproval'])->name('mayor.leave.approve.process');
    Route::get('/mayor-profile', function () {
        return view('mayor_profile');
    })->name('mayor.profile');
    Route::get('/mayor-settings', function () {
        return view('mayor_settings');
    })->name('mayor.settings');
});
