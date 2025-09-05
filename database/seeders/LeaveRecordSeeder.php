<?php

namespace Database\Seeders;

use App\Models\LeaveRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        
        // Delete all existing leave records
        LeaveRecord::truncate();
        
        // Create sample leave records for each user
        foreach ($users as $user) {
            // Create records for the last 12 months
            for ($i = 0; $i < 12; $i++) {
                $date = now()->subMonths($i);
                $month = $date->month;
                $year = $date->year;
                
                LeaveRecord::create([
                    'user_id' => $user->user_id,
                    'month' => $month,
                    'year' => $year,
                    'vacation_earned' => 1.25,
                    'vacation_used' => $this->getRandomVacationUsed($user->user_id, $month),
                    'vacation_balance' => $this->calculateVacationBalance($user->user_id, $month, $year),
                    'sick_earned' => 1.25,
                    'sick_used' => $this->getRandomSickUsed($user->user_id, $month),
                    'sick_balance' => $this->calculateSickBalance($user->user_id, $month, $year),
                    'undertime_hours' => $this->getRandomUndertime($user->user_id, $month),
                    'vacation_entries' => $this->generateVacationEntries($user->user_id, $month, $year),
                    'sick_entries' => $this->generateSickEntries($user->user_id, $month, $year),
                ]);
            }
        }
        
        $this->command->info('Leave records seeded successfully for ' . $users->count() . ' users.');
    }
    
    private function getRandomVacationUsed($userId, $month)
    {
        // Create a user-specific seed based on user ID and month
        $seed = crc32($userId . $month) % 100;
        
        // Different probabilities for vacation usage
        if ($seed < 5) {
            return 0; // 5% chance of no vacation used
        } elseif ($seed < 15) {
            return 0.5; // 10% chance of 0.5 days
        } elseif ($seed < 30) {
            return 1.0; // 15% chance of 1 day
        } elseif ($seed < 45) {
            return 1.5; // 15% chance of 1.5 days
        } elseif ($seed < 60) {
            return 2.0; // 15% chance of 2 days
        } elseif ($seed < 70) {
            return 2.5; // 10% chance of 2.5 days
        } elseif ($seed < 80) {
            return 3.0; // 10% chance of 3 days
        } else {
            return 0; // 20% chance of no vacation used (December gets special handling)
        }
        
        // Special case for December (holiday season)
        if ($month == 12) {
            $decemberSeed = crc32($userId . 'december') % 100;
            if ($decemberSeed < 30) {
                return 2.5; // 30% chance of Christmas vacation
            } elseif ($decemberSeed < 50) {
                return 3.0; // 20% chance of longer vacation
            } else {
                return 0; // 50% chance of no vacation
            }
        }
    }
    
    private function getRandomSickUsed($userId, $month)
    {
        // Create a user-specific seed based on user ID and month
        $seed = crc32($userId . 'sick' . $month) % 100;
        
        // Different probabilities for sick leave usage
        if ($seed < 20) {
            return 0; // 20% chance of no sick leave
        } elseif ($seed < 40) {
            return 0.5; // 20% chance of half day
        } elseif ($seed < 65) {
            return 1.0; // 25% chance of 1 day
        } elseif ($seed < 80) {
            return 1.5; // 15% chance of 1.5 days
        } elseif ($seed < 90) {
            return 2.0; // 10% chance of 2 days
        } else {
            return 0; // 10% chance of no sick leave
        }
        
        // Special case for November (flu season)
        if ($month == 11) {
            $novemberSeed = crc32($userId . 'november_sick') % 100;
            if ($novemberSeed < 25) {
                return 1.0; // 25% chance of 1 day sick leave
            } elseif ($novemberSeed < 35) {
                return 1.5; // 10% chance of 1.5 days
            } else {
                return 0; // 65% chance of no sick leave
            }
        }
    }
    
    private function getRandomUndertime($userId, $month)
    {
        // Create a user-specific seed based on user ID and month
        $seed = crc32($userId . 'undertime' . $month) % 100;
        
        // Different probabilities for undertime
        if ($seed < 30) {
            return 0; // 30% chance of no undertime
        } elseif ($seed < 50) {
            return 0.5; // 20% chance of 0.5 hours
        } elseif ($seed < 70) {
            return 1.0; // 20% chance of 1 hour
        } elseif ($seed < 85) {
            return 1.5; // 15% chance of 1.5 hours
        } elseif ($seed < 95) {
            return 2.0; // 10% chance of 2 hours
        } else {
            return 3.0; // 5% chance of 3 hours
        }
    }
    
    private function calculateVacationBalance($userId, $month, $year)
    {
        // Simple calculation for demo purposes
        $baseBalance = 5.0;
        $used = $this->getRandomVacationUsed($userId, $month);
        return max(0, $baseBalance - $used + 1.25);
    }
    
    private function calculateSickBalance($userId, $month, $year)
    {
        // Simple calculation for demo purposes
        $baseBalance = 50.0;
        $used = $this->getRandomSickUsed($userId, $month);
        return max(0, $baseBalance - $used + 1.25);
    }
    
    private function generateVacationEntries($userId, $month, $year)
    {
        $vacationUsed = $this->getRandomVacationUsed($userId, $month);
        
        if ($vacationUsed > 0) {
            // Create a user-specific seed for vacation entries
            $seed = crc32($userId . 'vacation_entries' . $month) % 100;
            
            if ($month == 12 && $seed < 50) {
                // Christmas vacation for some users
                return [
                    [
                        'start_date' => "$year-12-23",
                        'end_date' => "$year-12-26",
                        'days' => min(2.5, $vacationUsed),
                        'description' => 'Christmas vacation'
                    ]
                ];
            } elseif ($month == 7 && $seed < 30) {
                // Summer vacation for some users
                return [
                    [
                        'start_date' => "$year-07-15",
                        'end_date' => "$year-07-17",
                        'days' => min(1.5, $vacationUsed),
                        'description' => 'Summer break'
                    ]
                ];
            } elseif ($seed < 20) {
                // Random short vacation
                $startDay = rand(1, 20);
                $endDay = min($startDay + 1, 31);
                return [
                    [
                        'start_date' => sprintf("$year-%02d-%02d", $month, $startDay),
                        'end_date' => sprintf("$year-%02d-%02d", $month, $endDay),
                        'days' => min(1.0, $vacationUsed),
                        'description' => 'Personal time off'
                    ]
                ];
            }
        }
        
        return null;
    }
    
    private function generateSickEntries($userId, $month, $year)
    {
        $sickUsed = $this->getRandomSickUsed($userId, $month);
        
        if ($sickUsed > 0) {
            // Create a user-specific seed for sick entries
            $seed = crc32($userId . 'sick_entries' . $month) % 100;
            
            if ($month == 11 && $seed < 30) {
                // Doctor appointment in November for some users
                return [
                    [
                        'date' => "$year-11-15",
                        'days' => min(1.0, $sickUsed),
                        'description' => 'Doctor appointment'
                    ]
                ];
            } elseif ($seed < 25) {
                // Random sick day
                $day = rand(1, 28);
                return [
                    [
                        'date' => sprintf("$year-%02d-%02d", $month, $day),
                        'days' => min(1.0, $sickUsed),
                        'description' => 'Medical leave'
                    ]
                ];
            }
        }
        
        return null;
    }
}