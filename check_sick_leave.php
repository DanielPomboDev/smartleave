<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Check leave records for EMP001
$user = User::where('user_id', 'EMP001')->first();
if (!$user) {
    echo "User EMP001 not found\n";
    exit(1);
}

// Get all leave records for this user, ordered by year/month descending (most recent first)
$allLeaveRecords = $user->leaveRecords()->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

echo "SICK LEAVE RECORDS FOR EMP001:\n";
echo "User ID\tMonth\tYear\tEarned\tUsed\tBalance\n";

$totalSickEarned = 0;
$totalSickUsed = 0;

foreach ($allLeaveRecords as $record) {
    echo $record->user_id . "\t" . $record->month . "\t" . $record->year . "\t" . 
         $record->sick_earned . "\t" . $record->sick_used . "\t" . $record->sick_balance . "\n";
    
    $totalSickEarned += $record->sick_earned;
    $totalSickUsed += $record->sick_used;
}

echo "\nSICK LEAVE SUMMARY:\n";
echo "Total Earned: " . $totalSickEarned . "\n";
echo "Total Used: " . $totalSickUsed . "\n";
echo "Total Balance (sum): " . $allLeaveRecords->sum('sick_balance') . "\n";
echo "Current Balance (most recent): " . ($allLeaveRecords->isNotEmpty() ? $allLeaveRecords->first()->sick_balance : 0) . "\n";

echo "\nVACATION LEAVE SUMMARY:\n";
echo "Total Earned: " . $allLeaveRecords->sum('vacation_earned') . "\n";
echo "Total Used: " . $allLeaveRecords->sum('vacation_used') . "\n";
echo "Current Balance (most recent): " . ($allLeaveRecords->isNotEmpty() ? $allLeaveRecords->first()->vacation_balance : 0) . "\n";
