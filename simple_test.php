<?php

// Simple test without Laravel bootstrap
require_once __DIR__.'/vendor/autoload.php';

// Test the LeaveCreditCalculator directly
use App\Services\LeaveCreditCalculator;

echo "TESTING LeaveCreditCalculator:\n";
echo "===============================\n";

// Test case: 13 days present, 17 LWOP
$result1 = LeaveCreditCalculator::calculateProratedCredits(13.00, 17.00, 30.00);
echo "13 days present, 17 LWOP: " . $result1 . "\n";

// Test case: 18 days present, 12 LWOP
$result2 = LeaveCreditCalculator::calculateProratedCredits(18.00, 12.00, 30.00);
echo "18 days present, 12 LWOP: " . $result2 . "\n";

echo "\nEXPECTED VALUES FROM leavecredits FILE:\n";
echo "=====================================\n";
echo "13 days present, 17 LWOP: 0.708\n";
echo "18 days present, 12 LWOP: 0.750\n";

// Let's also manually check if the reference data has these values
echo "\nCHECKING REFERENCE DATA:\n";
echo "========================\n";

// Create a simple version of the reference data to check
$referenceData = [
    ['present' => 30.00, 'leave_wo_pay' => 0.00, 'credits' => 1.250],
    ['present' => 29.50, 'leave_wo_pay' => 0.50, 'credits' => 1.229],
    ['present' => 29.00, 'leave_wo_pay' => 1.00, 'credits' => 1.208],
    // ... (abbreviated for clarity)
    ['present' => 18.00, 'leave_wo_pay' => 12.00, 'credits' => 0.750],
    // ... (abbreviated for clarity)
    ['present' => 13.00, 'leave_wo_pay' => 17.00, 'credits' => 0.708],
    // ... (abbreviated for clarity)
    ['present' => 0.00, 'leave_wo_pay' => 30.00, 'credits' => 0.000],
];

// Check for exact matches
foreach ($referenceData as $data) {
    if ($data['present'] == 13.00 && $data['leave_wo_pay'] == 17.00) {
        echo "Found in reference data: 13 present, 17 LWOP = " . $data['credits'] . "\n";
    }
    if ($data['present'] == 18.00 && $data['leave_wo_pay'] == 12.00) {
        echo "Found in reference data: 18 present, 12 LWOP = " . $data['credits'] . "\n";
    }
}