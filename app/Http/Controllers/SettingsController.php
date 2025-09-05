<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\NotificationPreference;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Password updated successfully. Please log in with your new password.');
    }
    
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_leave_requests' => 'boolean',
            'email_approvals' => 'boolean',
            'email_rejections' => 'boolean',
            'in_app_leave_requests' => 'boolean',
            'in_app_approvals' => 'boolean',
            'in_app_rejections' => 'boolean',
            'push_leave_requests' => 'boolean',
            'push_approvals' => 'boolean',
            'push_rejections' => 'boolean',
        ]);
        
        $user = Auth::user();
        
        // Update or create notification preferences
        $user->notificationPreferences()->updateOrCreate(
            ['user_id' => $user->user_id],
            $validated
        );
        
        return back()->with('success', 'Notification settings updated successfully!');
    }
}
