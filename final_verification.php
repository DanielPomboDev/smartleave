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

echo "EMP001 LEAVE SUMMARY:\n";
echo "====================\n\n";

echo "VACATION LEAVE:\n";
echo "---------------\n";
echo "Total Earned: " . $allLeaveRecords->sum('vacation_earned') . " days\n";
echo "Total Used: " . $allLeaveRecords->sum('vacation_used') . " days\n";
echo "Current Balance: " . ($allLeaveRecords->isNotEmpty() ? $allLeaveRecords->first()->vacation_balance : 0) . " days\n";

echo "\nSICK LEAVE:\n";
echo "-----------\n";
echo "Total Earned: " . $allLeaveRecords->sum('sick_earned') . " days\n";
echo "Total Used: " . $allLeaveRecords->sum('sick_used') . " days\n";
echo "Current Balance: " . ($allLeaveRecords->isNotEmpty() ? $allLeaveRecords->first()->sick_balance : 0) . " days\n";

echo "\nDETAILED RECORDS:\n";
echo "================\n";
echo "Month/Year\tVacation Earned\tVacation Used\tVacation Balance\tSick Earned\tSick Used\tSick Balance\n";

foreach ($allLeaveRecords->reverse() as $record) {
    echo $record->month . "/" . $record->year . "\t\t" . 
         $record->vacation_earned . "\t\t" . $record->vacation_used . "\t\t" . $record->vacation_balance . "\t\t" .
         $record->sick_earned . "\t\t" . $record->sick_used . "\t\t" . $record->sick_balance . "\n";
}