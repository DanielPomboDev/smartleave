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

// Simulate applying 12 LWOP days instead of 17
$newLwopDays = 12;
$workingDaysInMonth = 30;
$daysPresent = $workingDaysInMonth - $newLwopDays;

echo "\nSIMULATING 12 LWOP DAYS:\n";
echo "========================\n";
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

// Let's also check what the record would look like with 17 LWOP days
$existingLwopDays = 17;
$existingDaysPresent = $workingDaysInMonth - $existingLwopDays;
$existingVacationCredits = LeaveCreditCalculator::calculateProratedCredits(
    $existingDaysPresent, 
    $existingLwopDays, 
    $workingDaysInMonth
);

echo "\nFOR COMPARISON - 17 LWOP DAYS:\n";
echo "=============================\n";
echo "LWOP Days: " . $existingLwopDays . "\n";
echo "Days Present: " . $existingDaysPresent . "\n";
echo "Calculated Vacation Credits: " . $existingVacationCredits . "\n";

// Check what's in the leavecredits file for these values
echo "\nVERIFICATION FROM leavecredits FILE:\n";
echo "===================================\n";
echo "For 13 days LWOP (17 present): Vacation Credits = 0.708\n";
echo "For 12 days LWOP (18 present): Vacation Credits = 0.750\n";