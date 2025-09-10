<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\LeaveCreditCalculator;

// Check the reference data in the calculator
$reflection = new ReflectionClass(LeaveCreditCalculator);
$method = $reflection->getMethod('calculateProratedCredits');
$method->setAccessible(true);

// Test specific cases
echo "TESTING LeaveCreditCalculator:\n";
echo "===============================\n";

// Test case: 13 days present, 17 LWOP
$result1 = LeaveCreditCalculator::calculateProratedCredits(13.00, 17.00, 30.00);
echo "13 days present, 17 LWOP: " . $result1 . "\n";

// Test case: 17 days present, 13 LWOP
$result2 = LeaveCreditCalculator::calculateProratedCredits(17.00, 13.00, 30.00);
echo "17 days present, 13 LWOP: " . $result2 . "\n";

// Test case: 18 days present, 12 LWOP
$result3 = LeaveCreditCalculator::calculateProratedCredits(18.00, 12.00, 30.00);
echo "18 days present, 12 LWOP: " . $result3 . "\n";

echo "\nEXPECTED VALUES FROM leavecredits FILE:\n";
echo "=====================================\n";
echo "13 days present, 17 LWOP: 0.708\n";
echo "17 days present, 13 LWOP: 0.708\n";
echo "18 days present, 12 LWOP: 0.750\n";

echo "\nDIRECT LOOKUP IN REFERENCE DATA:\n";
echo "===============================\n";

// Let's check if the reference data contains these exact values
$calculator = new LeaveCreditCalculator();
$reflection = new ReflectionClass($calculator);
$property = $reflection->getProperty('referenceData');
$property->setAccessible(true);
$referenceData = $property->getValue(null);

// Look for 13 present, 17 LWOP
$found = false;
foreach ($referenceData as $data) {
    if ($data['present'] == 13.00 && $data['leave_wo_pay'] == 17.00) {
        echo "Found exact match for 13 present, 17 LWOP: " . $data['credits'] . "\n";
        $found = true;
        break;
    }
}

if (!$found) {
    echo "No exact match found for 13 present, 17 LWOP\n";
}

// Look for 18 present, 12 LWOP
$found = false;
foreach ($referenceData as $data) {
    if ($data['present'] == 18.00 && $data['leave_wo_pay'] == 12.00) {
        echo "Found exact match for 18 present, 12 LWOP: " . $data['credits'] . "\n";
        $found = true;
        break;
    }
}

if (!$found) {
    echo "No exact match found for 18 present, 12 LWOP\n";
}