<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\LeaveRecord;

class InsertSampleLeaveRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:insert-sample-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert sample leave records for EMP001 from January 2025 to September 2025';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if EMP001 exists
        $user = User::where('user_id', 'EMP001')->first();

        if (!$user) {
            $this->error('User EMP001 not found in the database.');
            return 1;
        }

        $this->info('Inserting sample leave records for EMP001 from January 2025 to September 2025...');

        // Sample data for EMP001
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
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
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
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
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
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
            ],
            // April 2025 - 2 days LWOP
            [
                'user_id' => 'EMP001',
                'month' => 4,
                'year' => 2025,
                'vacation_earned' => 1.167, // Prorated for 28 days present (30-2)
                'vacation_used' => 0,
                'vacation_balance' => 4.917, // Previous balance + earned
                'sick_earned' => 1.167, // Prorated for 28 days present (30-2)
                'sick_used' => 0,
                'sick_balance' => 4.917, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 2,
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
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
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
            ],
            // June 2025 - 1 day LWOP
            [
                'user_id' => 'EMP001',
                'month' => 6,
                'year' => 2025,
                'vacation_earned' => 1.208, // Prorated for 29 days present (30-1)
                'vacation_used' => 0,
                'vacation_balance' => 7.375, // Previous balance + earned
                'sick_earned' => 1.208, // Prorated for 29 days present (30-1)
                'sick_used' => 0,
                'sick_balance' => 7.375, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 1,
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
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
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
            ],
            // August 2025 - 3 days LWOP
            [
                'user_id' => 'EMP001',
                'month' => 8,
                'year' => 2025,
                'vacation_earned' => 1.125, // Prorated for 27 days present (30-3)
                'vacation_used' => 0,
                'vacation_balance' => 9.750, // Previous balance + earned
                'sick_earned' => 1.125, // Prorated for 27 days present (30-3)
                'sick_used' => 0,
                'sick_balance' => 9.750, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 3,
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
            ],
            // September 2025 - 5 days LWOP (as per your example)
            [
                'user_id' => 'EMP001',
                'month' => 9,
                'year' => 2025,
                'vacation_earned' => 1.042, // Prorated for 25 days present (30-5)
                'vacation_used' => 0,
                'vacation_balance' => 10.792, // Previous balance + earned
                'sick_earned' => 1.042, // Prorated for 25 days present (30-5)
                'sick_used' => 0,
                'sick_balance' => 10.792, // Previous balance + earned
                'undertime_hours' => 0,
                'lwop_days' => 5,
                'vacation_entries' => json_encode([]),
                'sick_entries' => json_encode([])
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
                $this->info("Inserted leave record for {$record['year']}-{$record['month']}");
            } else {
                $this->line("Leave record for {$record['year']}-{$record['month']} already exists");
            }
        }

        $this->info('Sample leave records inserted successfully!');
        return 0;
    }
}