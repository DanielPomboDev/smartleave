<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            // Check if notification preferences already exist for this user
            $existing = NotificationPreference::where('user_id', $user->user_id)->first();
            
            if (!$existing) {
                NotificationPreference::create([
                    'user_id' => $user->user_id,
                    'email_leave_requests' => true,
                    'email_approvals' => true,
                    'email_rejections' => true,
                    'in_app_leave_requests' => true,
                    'in_app_approvals' => true,
                    'in_app_rejections' => true,
                    'push_leave_requests' => false,
                    'push_approvals' => false,
                    'push_rejections' => false,
                ]);
            }
        }
    }
}
