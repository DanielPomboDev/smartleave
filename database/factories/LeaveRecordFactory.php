<?php

namespace Database\Factories;

use App\Models\LeaveRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRecordFactory extends Factory
{
    protected $model = LeaveRecord::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2020, 2025),
            'vacation_earned' => 1.25,
            'vacation_used' => $this->faker->randomElement([0, 0.5, 1.0, 1.25, 2.5]),
            'vacation_balance' => $this->faker->randomFloat(3, 0, 20),
            'sick_earned' => 1.25,
            'sick_used' => $this->faker->randomElement([0, 0.5, 1.0]),
            'sick_balance' => $this->faker->randomFloat(3, 0, 60),
            'undertime_hours' => $this->faker->randomFloat(2, 0, 5),
            'vacation_entries' => $this->generateVacationEntries(),
            'sick_entries' => $this->generateSickEntries(),
        ];
    }

    private function generateVacationEntries()
    {
        if ($this->faker->boolean(70)) {
            return null;
        }

        $entries = [];
        $count = $this->faker->numberBetween(1, 3);

        for ($i = 0; $i < $count; $i++) {
            $startDate = $this->faker->dateTimeThisYear();
            $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 5) . ' days');
            
            $entries[] = [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $this->faker->randomFloat(1, 0.5, 5),
                'description' => $this->faker->sentence()
            ];
        }

        return $entries;
    }

    private function generateSickEntries()
    {
        if ($this->faker->boolean(80)) {
            return null;
        }

        $entries = [];
        $count = $this->faker->numberBetween(1, 2);

        for ($i = 0; $i < $count; $i++) {
            $date = $this->faker->dateTimeThisYear()->format('Y-m-d');
            
            $entries[] = [
                'date' => $date,
                'days' => $this->faker->randomFloat(1, 0.5, 3),
                'description' => $this->faker->sentence()
            ];
        }

        return $entries;
    }
}