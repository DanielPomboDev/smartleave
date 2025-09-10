<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LeaveRecord;
use App\Models\User;

// Get EMP001's September 2025 record
$user = User::where('user_id', 'EMP001')->first();
if (!$user) {
    echo "User EMP001 not found\n";
    exit(1);
}

$septemberRecord = LeaveRecord::where('user_id', 'EMP001')
    ->where('month', 9)
    ->where('year', 2025)
    ->first();

if (!$septemberRecord) {
    echo "September 2025 record not found for EMP001\n";
    exit(1);
}

echo "EMP001 - September 2025 Leave Record BEFORE any changes:\n";
echo "=====================================================\n";
echo "Vacation Earned: " . $septemberRecord->vacation_earned . "\n";
echo "Vacation Used: " . $septemberRecord->vacation_used . "\n";
echo "Vacation Balance: " . $septemberRecord->vacation_balance . "\n";
echo "Sick Earned: " . $septemberRecord->sick_earned . "\n";
echo "Sick Used: " . $septemberRecord->sick_used . "\n";
echo "Sick Balance: " . $septemberRecord->sick_balance . "\n";
echo "LWOP Days: " . $septemberRecord->lwop_days . "\n";
echo "Undertime Hours: " . $septemberRecord->undertime_hours . "\n";

// Let's simulate what SHOULD happen with 12 LWOP days
// According to the leavecredits table, for 18 days present (30-12) and 12 LWOP days:
// Vacation earned should be 0.750 days

echo "\nEXPECTED CALCULATION FOR 12 LWOP DAYS:\n";
echo "=====================================\n";
echo "Days Present: 18 (30 - 12 LWOP)\n";
echo "LWOP Days: 12\n";
echo "Expected Vacation Earned: 0.750 days (from leavecredits table)\n";
echo "Expected Sick Earned: 0.750 days (if also prorated)\n";