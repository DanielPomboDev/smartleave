<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Fetch unread notifications for the authenticated user
     */
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        
        // Get unread notifications for the user
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $user->user_id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                // Convert created_at to ISO 8601 format for JavaScript
                if ($notification->created_at) {
                    $date = new \DateTime($notification->created_at);
                    $date->setTimezone(new \DateTimeZone(config('app.timezone', 'UTC')));
                    $notification->created_at = $date->format('c');
                }
                return $notification;
            });
            
        return response()->json($notifications);
    }
    
    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        // Update the notification
        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $user->user_id)
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        // Update all unread notifications
        DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $user->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
}