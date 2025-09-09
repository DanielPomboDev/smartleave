<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check all notifications
$notifications = DB::table('notifications')->get();

echo "Total notifications in database: " . $notifications->count() . "\n";

foreach ($notifications as $notification) {
    echo "Notification ID: " . $notification->id . "\n";
    echo "Type: " . $notification->type . "\n";
    echo "Notifiable Type: " . $notification->notifiable_type . "\n";
    echo "Notifiable ID: " . $notification->notifiable_id . "\n";
    echo "Data: " . $notification->data . "\n";
    echo "Read at: " . ($notification->read_at ?? 'Not read') . "\n";
    echo "---\n";
}