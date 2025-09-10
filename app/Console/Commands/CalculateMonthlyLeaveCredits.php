<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\LeaveRecord;
use App\Models\LeaveRequest;
use App\Models\LeaveApproval;
use App\Services\LeaveCreditCalculator;

class CalculateMonthlyLeaveCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:calculate-monthly-credits {--month=} {--year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and award monthly leave credits based on attendance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ?? now()->subMonth()->month;
        $year = $this->option('year') ?? now()->subMonth()->year;

        $this->info("Calculating leave credits for $month/$year...");

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            $this->calculateUserCredits($user, $month, $year);
        }

        $this->info('Monthly leave credits calculated successfully!');
    }

    /**
     * Calculate and award leave credits for a specific user
     */
    private function calculateUserCredits(User $user, int $month, int $year)
    {
        $this->info("Processing user: {$user->user_id} ({$user->first_name} {$user->last_name})");

        // Get or create leave record for the month
        $leaveRecord = LeaveRecord::firstOrCreate(
            [
                'user_id' => $user->user_id,
                'month' => $month,
                'year' => $year
            ],
            [
                'vacation_earned' => 0,
                'vacation_used' => 0,
                'vacation_balance' => 0,
                'sick_earned' => 0,
                'sick_used' => 0,
                'sick_balance' => 0,
                'undertime_hours' => 0,
                'lwop_days' => 0,
                'vacation_entries' => [],
                'sick_entries' => []
            ]
        );

        // Calculate LWOP days for the month from approved leave requests
        $lwopDays = $this->calculateLwopDays($user, $month, $year);
        
        // Update LWOP days in the record
        $leaveRecord->lwop_days = $lwopDays;
        $leaveRecord->save();

        // Calculate prorated credits using 30 working days as reference
        $workingDaysInMonth = 30;
        $daysPresent = $workingDaysInMonth - $lwopDays;
        
        // Ensure days present doesn't go below 0
        $daysPresent = max(0, $daysPresent);
        
        // Calculate prorated credits using the LeaveCreditCalculator (only for vacation)
        $proratedVacationCredits = LeaveCreditCalculator::calculateProratedCredits(
            $daysPresent, 
            $lwopDays, 
            $workingDaysInMonth
        );

        // Set the earned credits
        $leaveRecord->vacation_earned = $proratedVacationCredits;
        $leaveRecord->sick_earned = 1.250; // Always 1.250 for sick leave
        
        // Update balances (only add earned credits if this is the current month or a past month)
        if ($year < now()->year || ($year == now()->year && $month <= now()->month)) {
            $leaveRecord->vacation_balance += $proratedVacationCredits;
            $leaveRecord->sick_balance += 1.250;
        }

        $leaveRecord->save();

        $this->info("  LWOP days: $lwopDays, Vacation credits: $proratedVacationCredits, Sick credits: 1.250");
    }

    /**
     * Calculate LWOP days for a user in a specific month
     */
    private function calculateLwopDays(User $user, int $month, int $year)
    {
        $totalLwopDays = 0;

        // Get all approved leave requests for the user in the specified month
        $leaveRequests = LeaveRequest::where('user_id', $user->user_id)
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->where('status', 'approved')
            ->get();

        foreach ($leaveRequests as $leaveRequest) {
            // Check if this leave was approved as without pay
            $approval = LeaveApproval::where('leave_id', $leaveRequest->id)
                ->where('approval', 'approve')
                ->first();

            if ($approval && $approval->approved_for === 'without_pay') {
                // Add the number of days as LWOP days
                $totalLwopDays += $leaveRequest->number_of_days;
            }
        }

        return $totalLwopDays;
    }
}
