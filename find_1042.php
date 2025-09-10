<?php

// Check if there's a combination that gives 1.042
echo "Looking for combinations that result in 1.042 credits:\n";
echo "====================================================\n";

// Reference data from leavecredits file
$referenceData = [
    ['present' => 30.00, 'leave_wo_pay' => 0.00, 'credits' => 1.250],
    ['present' => 29.50, 'leave_wo_pay' => 0.50, 'credits' => 1.229],
    ['present' => 29.00, 'leave_wo_pay' => 1.00, 'credits' => 1.208],
    ['present' => 28.50, 'leave_wo_pay' => 1.50, 'credits' => 1.188],
    ['present' => 28.00, 'leave_wo_pay' => 2.00, 'credits' => 1.167],
    ['present' => 27.50, 'leave_wo_pay' => 2.50, 'credits' => 1.146],
    ['present' => 27.00, 'leave_wo_pay' => 3.00, 'credits' => 1.125],
    ['present' => 26.50, 'leave_wo_pay' => 3.50, 'credits' => 1.104],
    ['present' => 26.00, 'leave_wo_pay' => 4.00, 'credits' => 1.083],
    ['present' => 25.50, 'leave_wo_pay' => 4.50, 'credits' => 1.063],
    ['present' => 25.00, 'leave_wo_pay' => 5.00, 'credits' => 1.042],
    ['present' => 24.50, 'leave_wo_pay' => 5.50, 'credits' => 1.021],
    // ... (just showing a few for now)
];

foreach ($referenceData as $data) {
    if ($data['credits'] == 1.042) {
        echo "Found: " . $data['present'] . " present, " . $data['leave_wo_pay'] . " LWOP = " . $data['credits'] . " credits\n";
    }
}