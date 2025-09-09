<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check counts for each table
$leaveRequestsCount = DB::table('leave_requests')->count();
$leaveRecommendationsCount = DB::table('leave_recommendations')->count();
$leaveApprovalsCount = DB::table('leave_approvals')->count();
$notificationsCount = DB::table('notifications')->count();

echo "Leave requests count: " . $leaveRequestsCount . "\n";
echo "Leave recommendations count: " . $leaveRecommendationsCount . "\n";
echo "Leave approvals count: " . $leaveApprovalsCount . "\n";
echo "Notifications count: " . $notificationsCount . "\n";