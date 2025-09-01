<?php

namespace Database\Seeders;

use App\Models\LeaveRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }
        
        // Create sample leave records for each user
        foreach ($users as $user) {
            // Create records for the last 12 months
            for ($i = 0; $i < 12; $i++) {
                $date = now()->subMonths($i);
                $month = $date->month;
                $year = $date->year;
                
                // Check if record already exists
                $existing = LeaveRecord::where('user_id', $user->user_id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();
                
                if (!$existing) {
                    LeaveRecord::create([
                        'user_id' => $user->user_id,
                        'month' => $month,
                        'year' => $year,
                        'vacation_earned' => 1.25,
                        'vacation_used' => $this->getRandomVacationUsed($month),
                        'vacation_balance' => $this->calculateVacationBalance($user->user_id, $month, $year),
                        'sick_earned' => 1.25,
                        'sick_used' => $this->getRandomSickUsed($month),
                        'sick_balance' => $this->calculateSickBalance($user->user_id, $month, $year),
                        'undertime_hours' => $this->getRandomUndertime($month),
                        'vacation_entries' => $this->generateVacationEntries($month, $year),
                        'sick_entries' => $this->generateSickEntries($month, $year),
                    ]);
                }
            }
        }
    }
    
    private function getRandomVacationUsed($month)
    {
        $usage = [
            1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0,
            7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 2.5
        ];
        
        return $usage[$month] ?? 0;
    }
    
    private function getRandomSickUsed($month)
    {
        $usage = [
            1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0,
            7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 1.0, 12 => 0
        ];
        
        return $usage[$month] ?? 0;
    }
    
    private function getRandomUndertime($month)
    {
        $undertime = [
            1 => 1.25, 2 => 0.5, 3 => 0.75, 4 => 0, 5 => 0, 6 => 0,
            7 => 0, 8 => 0, 9 => 4.83, 10 => 1.25, 11 => 1.2, 12 => 1.02
        ];
        
        return $undertime[$month] ?? 0;
    }
    
    private function calculateVacationBalance($userId, $month, $year)
    {
        // Simple calculation for demo purposes
        $baseBalance = 5.0;
        $used = $this->getRandomVacationUsed($month);
        return max(0, $baseBalance - $used + 1.25);
    }
    
    private function calculateSickBalance($userId, $month, $year)
    {
        // Simple calculation for demo purposes
        $baseBalance = 50.0;
        $used = $this->getRandomSickUsed($month);
        return max(0, $baseBalance - $used + 1.25);
    }
    
    private function generateVacationEntries($month, $year)
    {
        if ($month == 12) {
            return [
                [
                    'start_date' => "$year-12-23",
                    'end_date' => "$year-12-26",
                    'days' => 2.5,
                    'description' => 'Christmas vacation'
                ]
            ];
        }
        
        return null;
    }
    
    private function generateSickEntries($month, $year)
    {
        if ($month == 11) {
            return [
                [
                    'date' => "$year-11-15",
                    'days' => 1.0,
                    'description' => 'Doctor appointment'
                ]
            ];
        }
        
        return null;
    }
}