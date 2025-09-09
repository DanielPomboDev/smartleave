<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\LeaveRequestWorkflowNotification;
use App\Models\LeaveRequest;

class TestNotificationCommand extends Command
{
    protected $signature = 'test:notification';
    protected $description = 'Send a test notification';

    public function handle()
    {
        // Get a user to test with (first user in the database)
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
            
            $this->info("Test notification sent to user: " . $user->first_name . " " . $user->last_name);
            $this->info("Notification count: " . $user->notifications()->count());
        } else {
            $this->error("No users found in the database.");
        }
        
        return 0;
    }
}