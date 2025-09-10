<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\LeaveRecord;
use App\Models\LeaveRequest;
use App\Models\LeaveApproval;
use App\Services\LeaveCreditCalculator;

class SampleLeaveRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if EMP001 exists
        $user = User::where('user_id', 'EMP001')->first();

        if (!$user) {
            echo "User EMP001 not found in the database.
";
            return;
        }

        echo "Inserting sample leave records for EMP001 from January 2025 to September 2025...
";

        // Sample data for EMP001 - sick leave always 1.250, vacation affected by attendance
        $leaveRecords = [
            // January 2025 - Full attendance
            [
                'user_id' => 'EMP001',
                'month' => 1,
                'year' => 2025,
                'vacation_earned' => 1.250,
                'vacation_used' => 0,
                'vacation_balance' => 1.250, // Starting balance
                'sick_earned' => 1.250,
                'sick_used' => 0,
                'sick_balance' => 1.250, // Starting balance
                'undertime_hours' => 0,
                'lwop_days' => 0,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // February 2025 - Full attendance
            [
                'user_id' => 'EMP001',
                'month' => 2,
                'year' => 2025,
                'vacation_earned' => 1.250,
                'vacation_used' => 0,
                'vacation_balance' => 2.500, // Previous balance + earned
                'sick_earned' => 1.250,
                'sick_used' => 0,
                'sick_balance' => 2.500, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 0,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // March 2025 - Full attendance
            [
                'user_id' => 'EMP001',
                'month' => 3,
                'year' => 2025,
                'vacation_earned' => 1.250,
                'vacation_used' => 0,
                'vacation_balance' => 3.750, // Previous balance + earned
                'sick_earned' => 1.250,
                'sick_used' => 0,
                'sick_balance' => 3.750, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 0,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // April 2025 - 2 days without pay (vacation affected)
            [
                'user_id' => 'EMP001',
                'month' => 4,
                'year' => 2025,
                'vacation_earned' => 1.167, // Prorated for 28 days present (30-2)
                'vacation_used' => 0,
                'vacation_balance' => 4.917, // Previous balance + earned
                'sick_earned' => 1.250, // Always 1.250 for sick leave
                'sick_used' => 0,
                'sick_balance' => 4.917, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 2,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // May 2025 - Full attendance
            [
                'user_id' => 'EMP001',
                'month' => 5,
                'year' => 2025,
                'vacation_earned' => 1.250,
                'vacation_used' => 0,
                'vacation_balance' => 6.167, // Previous balance + earned
                'sick_earned' => 1.250,
                'sick_used' => 0,
                'sick_balance' => 6.167, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 0,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // June 2025 - 1 day without pay (vacation affected)
            [
                'user_id' => 'EMP001',
                'month' => 6,
                'year' => 2025,
                'vacation_earned' => 1.208, // Prorated for 29 days present (30-1)
                'vacation_used' => 0,
                'vacation_balance' => 7.375, // Previous balance + earned
                'sick_earned' => 1.250, // Always 1.250 for sick leave
                'sick_used' => 0,
                'sick_balance' => 7.375, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 1,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // July 2025 - Full attendance
            [
                'user_id' => 'EMP001',
                'month' => 7,
                'year' => 2025,
                'vacation_earned' => 1.250,
                'vacation_used' => 0,
                'vacation_balance' => 8.625, // Previous balance + earned
                'sick_earned' => 1.250,
                'sick_used' => 0,
                'sick_balance' => 8.625, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 0,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // August 2025 - 3 days without pay (vacation affected)
            [
                'user_id' => 'EMP001',
                'month' => 8,
                'year' => 2025,
                'vacation_earned' => 1.125, // Prorated for 27 days present (30-3)
                'vacation_used' => 0,
                'vacation_balance' => 9.750, // Previous balance + earned
                'sick_earned' => 1.250, // Always 1.250 for sick leave
                'sick_used' => 0,
                'sick_balance' => 9.750, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 3,
                'vacation_entries' => [],
                'sick_entries' => []
            ],
            // September 2025 - 5 days without pay (vacation affected)
            [
                'user_id' => 'EMP001',
                'month' => 9,
                'year' => 2025,
                'vacation_earned' => 1.042, // Prorated for 25 days present (30-5)
                'vacation_used' => 0,
                'vacation_balance' => 10.792, // Previous balance + earned
                'sick_earned' => 1.250, // Always 1.250 for sick leave
                'sick_used' => 0,
                'sick_balance' => 10.792, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 5,
                'vacation_entries' => [],
                'sick_entries' => []
            ]
        ];

        // Insert the leave records
        foreach ($leaveRecords as $record) {
            // Check if record already exists
            $exists = LeaveRecord::where('user_id', $record['user_id'])
                ->where('month', $record['month'])
                ->where('year', $record['year'])
                ->first();
            
            if (!$exists) {
                LeaveRecord::create($record);
                echo "Inserted leave record for {$record['year']}-{$record['month']}
";
            } else {
                echo "Leave record for {$record['year']}-{$record['month']} already exists
";
            }
        }

        echo "Sample leave records inserted successfully!
";
    }
}
