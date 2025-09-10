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

echo "Leave records for EMP001:\n";
echo "User ID\tMonth\tYear\tEarned\tUsed\tBalance\n";

foreach ($allLeaveRecords as $record) {
    echo $record->user_id . "\t" . $record->month . "\t" . $record->year . "\t" . 
         $record->vacation_earned . "\t" . $record->vacation_used . "\t" . $record->vacation_balance . "\n";
}

echo "\nOld incorrect calculation (sum of all balances):\n";
echo "Total Balance (sum): " . $allLeaveRecords->sum('vacation_balance') . "\n";

echo "\nNew correct calculation (most recent balance):\n";
$correctBalance = $allLeaveRecords->isNotEmpty() ? $allLeaveRecords->first()->vacation_balance : 0;
echo "Current Balance: " . $correctBalance . "\n";

echo "\nTotal Earned: " . $allLeaveRecords->sum('vacation_earned') . "\n";
echo "Total Used: " . $allLeaveRecords->sum('vacation_used') . "\n";