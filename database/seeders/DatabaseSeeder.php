<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create HR user
        User::create([
            'user_id' => 'HR001',
            'first_name' => 'HR',
            'middle_initial' => 'A',
            'last_name' => 'Admin',
            'office' => 'main',
            'position' => 'HR Manager',
            'salary' => 50000.00,
            'start_date' => now()->subYears(2),
            'password' => 'password',
            'user_type' => 'hr',
        ]);

        // Create Department Admin user
        User::create([
            'user_id' => 'DA001',
            'first_name' => 'Department',
            'middle_initial' => 'B',
            'last_name' => 'Admin',
            'office' => 'branch1',
            'position' => 'Department Head',
            'salary' => 45000.00,
            'start_date' => now()->subYear(),
            'password' => 'password',
            'user_type' => 'department',
        ]);

        // Create Standard Employee user
        User::create([
            'user_id' => 'EMP001',
            'first_name' => 'Myrna',
            'middle_initial' => 'O',
            'last_name' => 'Quintua',
            'office' => 'Accounting Office',
            'position' => 'Employee',
            'salary' => 35000.00,
            'start_date' => '2002-01-25',
            'password' => 'password',
            'user_type' => 'employee',
        ]);
    }
}
