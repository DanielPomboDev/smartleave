<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
        ]);

        // Create Department Admin user
        User::create([
            'user_id' => 'DA001',
            'first_name' => 'Department',
            'middle_initial' => 'B',
            'last_name' => 'Admin',
            'department_id' => 1, // Assuming department ID 1 is 'Accounting Office'
            'position' => 'Department Head',
            'salary' => 45000.00,
            'start_date' => now()->subYear(),
            'password' => Hash::make('password'),
            'user_type' => 'department',
        ]);

        // Create Standard Employee user
        User::create([
            'user_id' => 'EMP001',
            'first_name' => 'Myrna',
            'middle_initial' => 'O',
            'last_name' => 'Quintua',
            'department_id' => 1, // Assuming department ID 1 is 'Accounting Office'
            'position' => 'Employee',
            'salary' => 35000.00,
            'start_date' => '2002-01-25',
            'password' => Hash::make('password'),
            'user_type' => 'employee',
        ]);
    }
}
