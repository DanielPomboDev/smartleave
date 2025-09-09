<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Check if there are any users
$users = User::all();
echo "Total users: " . $users->count() . "\n";

foreach ($users as $user) {
    echo "User: " . $user->first_name . " " . $user->last_name . " (" . $user->user_type . ")\n";
    echo "Notification count: " . $user->notifications()->count() . "\n";
    echo "Unread notification count: " . $user->unreadNotifications()->count() . "\n";
    
    // Show recent notifications
    $recentNotifications = $user->notifications()->latest()->take(5)->get();
    foreach ($recentNotifications as $notification) {
        $data = json_decode($notification->data, true);
        echo "  - " . ($data['message'] ?? 'No message') . " (" . $notification->created_at . ")\n";
    }
    echo "\n";
}

// Also check the raw notifications table
$totalNotifications = DB::table('notifications')->count();
echo "Total notifications in database: " . $totalNotifications . "\n";