<?php

namespace App\Services;

class LeaveCreditCalculator
{
    /**
     * Calculate prorated vacation leave credits based on attendance
     * 
     * @param float $daysPresent Number of days present
     * @param float $daysLeaveWithoutPay Number of days on leave without pay
     * @param float $workingDays Total working days in the month
     * @return float Prorated leave credits
     */
    public static function calculateProratedCredits(float $daysPresent, float $daysLeaveWithoutPay, float $workingDays = 30.00): float
    {
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
            ['present' => 24.00, 'leave_wo_pay' => 6.00, 'credits' => 1.000],
            ['present' => 23.50, 'leave_wo_pay' => 6.50, 'credits' => 0.979],
            ['present' => 23.00, 'leave_wo_pay' => 7.00, 'credits' => 0.958],
            ['present' => 22.50, 'leave_wo_pay' => 7.50, 'credits' => 0.938],
            ['present' => 22.00, 'leave_wo_pay' => 8.00, 'credits' => 0.914],
            ['present' => 21.50, 'leave_wo_pay' => 8.50, 'credits' => 0.896],
            ['present' => 21.00, 'leave_wo_pay' => 9.00, 'credits' => 0.875],
            ['present' => 20.50, 'leave_wo_pay' => 9.50, 'credits' => 0.854],
            ['present' => 20.00, 'leave_wo_pay' => 10.00, 'credits' => 0.833],
            ['present' => 19.50, 'leave_wo_pay' => 10.50, 'credits' => 0.813],
            ['present' => 19.00, 'leave_wo_pay' => 11.00, 'credits' => 0.792],
            ['present' => 18.50, 'leave_wo_pay' => 11.50, 'credits' => 0.771],
            ['present' => 18.00, 'leave_wo_pay' => 12.00, 'credits' => 0.750],
            ['present' => 17.50, 'leave_wo_pay' => 12.50, 'credits' => 0.729],
            ['present' => 17.00, 'leave_wo_pay' => 13.00, 'credits' => 0.708],
            ['present' => 16.50, 'leave_wo_pay' => 13.50, 'credits' => 0.687],
            ['present' => 16.00, 'leave_wo_pay' => 14.00, 'credits' => 0.667],
            ['present' => 15.50, 'leave_wo_pay' => 14.50, 'credits' => 0.646],
            ['present' => 15.00, 'leave_wo_pay' => 15.00, 'credits' => 0.625],
            ['present' => 14.50, 'leave_wo_pay' => 15.50, 'credits' => 0.604],
            ['present' => 14.00, 'leave_wo_pay' => 16.00, 'credits' => 0.583],
            ['present' => 13.50, 'leave_wo_pay' => 16.50, 'credits' => 0.562],
            ['present' => 13.00, 'leave_wo_pay' => 17.00, 'credits' => 0.542],
            ['present' => 12.50, 'leave_wo_pay' => 17.50, 'credits' => 0.521],
            ['present' => 12.00, 'leave_wo_pay' => 18.00, 'credits' => 0.500],
            ['present' => 11.50, 'leave_wo_pay' => 18.50, 'credits' => 0.479],
            ['present' => 11.00, 'leave_wo_pay' => 19.00, 'credits' => 0.458],
            ['present' => 10.50, 'leave_wo_pay' => 19.50, 'credits' => 0.437],
            ['present' => 10.00, 'leave_wo_pay' => 20.00, 'credits' => 0.417],
            ['present' => 9.50, 'leave_wo_pay' => 20.50, 'credits' => 0.396],
            ['present' => 9.00, 'leave_wo_pay' => 21.00, 'credits' => 0.375],
            ['present' => 8.50, 'leave_wo_pay' => 21.50, 'credits' => 0.354],
            ['present' => 8.00, 'leave_wo_pay' => 22.00, 'credits' => 0.333],
            ['present' => 7.50, 'leave_wo_pay' => 22.50, 'credits' => 0.312],
            ['present' => 7.00, 'leave_wo_pay' => 23.00, 'credits' => 0.292],
            ['present' => 6.50, 'leave_wo_pay' => 23.50, 'credits' => 0.271],
            ['present' => 6.00, 'leave_wo_pay' => 24.00, 'credits' => 0.250],
            ['present' => 5.50, 'leave_wo_pay' => 24.50, 'credits' => 0.229],
            ['present' => 5.00, 'leave_wo_pay' => 25.00, 'credits' => 0.208],
            ['present' => 4.50, 'leave_wo_pay' => 25.50, 'credits' => 0.187],
            ['present' => 4.00, 'leave_wo_pay' => 26.00, 'credits' => 0.167],
            ['present' => 3.50, 'leave_wo_pay' => 26.50, 'credits' => 0.146],
            ['present' => 3.00, 'leave_wo_pay' => 27.00, 'credits' => 0.125],
            ['present' => 2.50, 'leave_wo_pay' => 27.50, 'credits' => 0.104],
            ['present' => 2.00, 'leave_wo_pay' => 28.00, 'credits' => 0.083],
            ['present' => 1.50, 'leave_wo_pay' => 28.50, 'credits' => 0.062],
            ['present' => 1.00, 'leave_wo_pay' => 29.00, 'credits' => 0.042],
            ['present' => 0.50, 'leave_wo_pay' => 29.50, 'credits' => 0.021],
            ['present' => 0.00, 'leave_wo_pay' => 30.00, 'credits' => 0.000],
        ];

        // Find the exact match or interpolate
        foreach ($referenceData as $data) {
            if ($data['present'] == $daysPresent && $data['leave_wo_pay'] == $daysLeaveWithoutPay) {
                return $data['credits'];
            }
        }

        // If no exact match, find the closest values and interpolate
        return self::interpolateCredits($daysPresent, $daysLeaveWithoutPay, $referenceData);
    }

    /**
     * Interpolate credits when exact match is not found
     * 
     * @param float $daysPresent
     * @param float $daysLeaveWithoutPay
     * @param array $referenceData
     * @return float
     */
    private static function interpolateCredits(float $daysPresent, float $daysLeaveWithoutPay, array $referenceData): float
    {
        // For simplicity, we'll find the closest match
        $closest = null;
        $minDiff = PHP_FLOAT_MAX;

        foreach ($referenceData as $data) {
            $diff = abs($data['present'] - $daysPresent) + abs($data['leave_wo_pay'] - $daysLeaveWithoutPay);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $data;
            }
        }

        return $closest ? $closest['credits'] : 1.250; // Default to full credits
    }

    /**
     * Validate attendance data
     * 
     * @param float $daysPresent
     * @param float $daysLeaveWithoutPay
     * @param float $workingDays
     * @return bool
     */
    public static function validateAttendanceData(float $daysPresent, float $daysLeaveWithoutPay, float $workingDays): bool
    {
        // Days present + days on leave without pay should not exceed working days
        return ($daysPresent + $daysLeaveWithoutPay) <= $workingDays;
    }
}