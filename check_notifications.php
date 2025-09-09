<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Get a user to test with
$user = User::first();

if ($user) {
    echo "User: " . $user->first_name . " " . $user->last_name . "\n";
    
    // Check notifications table
    $notifications = DB::table('notifications')->where('notifiable_type', 'App\Models\User')->where('notifiable_id', $user->user_id)->get();
    
    echo "Notification count in database: " . $notifications->count() . "\n";
    
    foreach ($notifications as $notification) {
        echo "Notification ID: " . $notification->id . "\n";
        echo "Type: " . $notification->type . "\n";
        echo "Data: " . $notification->data . "\n";
        echo "Read at: " . ($notification->read_at ?? 'Not read') . "\n";
        echo "---\n";
    }
} else {
    echo "No users found.\n";
}