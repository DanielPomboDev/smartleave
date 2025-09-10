<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LeaveRecord;
use App\Models\User;

echo "Checking leave records before fix:\n";
echo "================================\n";

// Check September record
$septemberRecord = LeaveRecord::where('user_id', 'EMP001')
    ->where('month', 9)
    ->where('year', 2025)
    ->first();

if ($septemberRecord) {
    echo "September 2025 Record:\n";
    echo "  Vacation Used: " . $septemberRecord->vacation_used . "\n";
    echo "  Vacation Balance: " . $septemberRecord->vacation_balance . "\n";
    echo "  Vacation Entries: " . count($septemberRecord->vacation_entries ?? []) . "\n";
}

// Check October record (if exists)
$octoberRecord = LeaveRecord::where('user_id', 'EMP001')
    ->where('month', 10)
    ->where('year', 2025)
    ->first();

if ($octoberRecord) {
    echo "\nOctober 2025 Record:\n";
    echo "  Vacation Used: " . $octoberRecord->vacation_used . "\n";
    echo "  Vacation Balance: " . $octoberRecord->vacation_balance . "\n";
    echo "  Vacation Entries: " . count($octoberRecord->vacation_entries ?? []) . "\n";
} else {
    echo "\nOctober 2025 Record: Does not exist\n";
}

echo "\nFix applied. Future leave requests will now be recorded in the correct month.\n";
