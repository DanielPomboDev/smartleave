<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check leave records for EMP001
$records = DB::table('leave_records')->where('user_id', 'EMP001')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

echo "Leave records for EMP001:\n";
echo "User ID\tMonth\tYear\tEarned\tUsed\tBalance\n";

$totalEarned = 0;
$totalUsed = 0;
$totalBalance = 0;

foreach ($records as $record) {
    echo $record->user_id . "\t" . $record->month . "\t" . $record->year . "\t" . 
         $record->vacation_earned . "\t" . $record->vacation_used . "\t" . $record->vacation_balance . "\n";
    
    $totalEarned += $record->vacation_earned;
    $totalUsed += $record->vacation_used;
    $totalBalance += $record->vacation_balance;
}

echo "\nTotals:\n";
echo "Total Earned: " . $totalEarned . "\n";
echo "Total Used: " . $totalUsed . "\n";
echo "Total Balance (sum): " . $totalBalance . "\n";

// Calculate correct cumulative balance
$correctBalance = 0;
echo "\nCorrect cumulative calculation:\n";
echo "Month/Year\tEarned\tUsed\tBalance\n";

foreach ($records->reverse() as $record) {
    $correctBalance = $correctBalance + $record->vacation_earned - $record->vacation_used;
    echo $record->month . "/" . $record->year . "\t" . $record->vacation_earned . "\t" . $record->vacation_used . "\t" . $correctBalance . "\n";
}

echo "\nCurrent correct balance: " . $correctBalance . "\n";