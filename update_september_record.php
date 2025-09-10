<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LeaveRecord;
use App\Models\User;
use App\Services\LeaveCreditCalculator;

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

echo "EMP001 - September 2025 Leave Record BEFORE update:\n";
echo "================================================\n";
echo "LWOP Days: " . $septemberRecord->lwop_days . "\n";
echo "Vacation Earned: " . $septemberRecord->vacation_earned . "\n";
echo "Vacation Balance: " . $septemberRecord->vacation_balance . "\n";
echo "Sick Earned: " . $septemberRecord->sick_earned . "\n";
echo "Sick Balance: " . $septemberRecord->sick_balance . "\n";

// Apply 12 LWOP days
$newLwopDays = 12;
$workingDaysInMonth = 30;
$daysPresent = $workingDaysInMonth - $newLwopDays;

echo "\nUPDATING TO 12 LWOP DAYS:\n";
echo "=========================\n";
echo "Working Days in Month: " . $workingDaysInMonth . "\n";
echo "LWOP Days: " . $newLwopDays . "\n";
echo "Days Present: " . $daysPresent . "\n";

// Calculate prorated credits using the LeaveCreditCalculator
$proratedVacationCredits = LeaveCreditCalculator::calculateProratedCredits(
    $daysPresent, 
    $newLwopDays, 
    $workingDaysInMonth
);

echo "Calculated Vacation Credits: " . $proratedVacationCredits . "\n";
echo "Sick Credits (always 1.250): 1.250\n";

// Update the record
$septemberRecord->lwop_days = $newLwopDays;
$septemberRecord->vacation_earned = $proratedVacationCredits;
$septemberRecord->sick_earned = 1.250;

// Recalculate the balance based on previous months
// We need to get the August balance and add the new earned credits
$augustRecord = LeaveRecord::where('user_id', 'EMP001')
    ->where('month', 8)
    ->where('year', 2025)
    ->first();

if ($augustRecord) {
    $newVacationBalance = $augustRecord->vacation_balance + $proratedVacationCredits;
    $newSickBalance = $augustRecord->sick_balance + 1.250;
    
    $septemberRecord->vacation_balance = $newVacationBalance;
    $septemberRecord->sick_balance = $newSickBalance;
    
    echo "\nBALANCE CALCULATIONS:\n";
    echo "====================\n";
    echo "Previous Vacation Balance (August): " . $augustRecord->vacation_balance . "\n";
    echo "New Vacation Balance (September): " . $newVacationBalance . "\n";
    echo "Previous Sick Balance (August): " . $augustRecord->sick_balance . "\n";
    echo "New Sick Balance (September): " . $newSickBalance . "\n";
} else {
    echo "Could not find August record for balance calculation\n";
}

$septemberRecord->save();

echo "\nEMP001 - September 2025 Leave Record AFTER update:\n";
echo "================================================\n";
echo "LWOP Days: " . $septemberRecord->lwop_days . "\n";
echo "Vacation Earned: " . $septemberRecord->vacation_earned . "\n";
echo "Vacation Balance: " . $septemberRecord->vacation_balance . "\n";
echo "Sick Earned: " . $septemberRecord->sick_earned . "\n";
echo "Sick Balance: " . $septemberRecord->sick_balance . "\n";

echo "\nSUCCESS: Leave record updated with correct calculations!\n";