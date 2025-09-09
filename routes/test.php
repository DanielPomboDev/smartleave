<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use App\Notifications\LeaveRequestWorkflowNotification;
use App\Models\LeaveRequest;

// Add a test route for notifications
Route::get('/test-notification', function () {
    // Get a user to test with
    $user = User::first();

    if ($user) {
        // Create a sample leave request
        $leaveRequest = new LeaveRequest([
            'user_id' => $user->user_id,
            'leave_type' => 'vacation',
            'subtype' => 'Personal',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(7),
            'number_of_days' => 3,
            'where_spent' => 'Within the Philippines',
            'commutation' => false,
            'status' => 'pending'
        ]);
        
        // Save the leave request
        $leaveRequest->save();
        
        // Send a test notification
        $user->notify(new LeaveRequestWorkflowNotification($leaveRequest, 'new_request'));
        
        return "Test notification sent to user: " . $user->first_name . " " . $user->last_name;
    } else {
        return "No users found in the database.";
    }
});