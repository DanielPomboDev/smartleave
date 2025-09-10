<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\LeaveRecord;

// Correct sick leave balances for EMP001
$correctBalances = [
    ['month' => 1, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 1.250],
    ['month' => 2, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 2.500],
    ['month' => 3, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 3.750],
    ['month' => 4, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 5.000],
    ['month' => 5, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 6.250],
    ['month' => 6, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 7.500],
    ['month' => 7, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 8.750],
    ['month' => 8, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 10.000],
    ['month' => 9, 'year' => 2025, 'sick_earned' => 1.250, 'sick_balance' => 11.250],
];

echo "Updating sick leave balances for EMP001...\n";

foreach ($correctBalances as $data) {
    $record = LeaveRecord::where('user_id', 'EMP001')
        ->where('month', $data['month'])
        ->where('year', $data['year'])
        ->first();
    
    if ($record) {
        $record->sick_earned = $data['sick_earned'];
        $record->sick_balance = $data['sick_balance'];
        $record->save();
        
        echo "Updated {$data['month']}/{$data['year']}: Sick Earned = {$data['sick_earned']}, Sick Balance = {$data['sick_balance']}\n";
    } else {
        echo "Record not found for {$data['month']}/{$data['year']}\n";
    }
}

echo "\nSick leave balances updated successfully!\n";

// Verify the update
echo "\nVerifying updated records:\n";
$records = LeaveRecord::where('user_id', 'EMP001')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

echo "Month/Year\tSick Earned\tSick Used\tSick Balance\n";
foreach ($records as $record) {
    echo $record->month . "/" . $record->year . "\t\t" . 
         $record->sick_earned . "\t\t" . $record->sick_used . "\t\t" . $record->sick_balance . "\n";
}

echo "\nSUMMARY:\n";
echo "Total Sick Earned: " . $records->sum('sick_earned') . "\n";
echo "Total Sick Used: " . $records->sum('sick_used') . "\n";
echo "Current Sick Balance: " . ($records->isNotEmpty() ? $records->first()->sick_balance : 0) . "\n";