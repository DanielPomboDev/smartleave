<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'user_id' => 'EMP002',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'password' => Hash::make('password'),
                'user_type' => 'employee',
                'department_id' => 2,
                'position' => 'HR Manager',
                'office' => 'Main Office',
                'salary' => 65000,
                'start_date' => '2022-03-20'
            ],
            [
                'user_id' => 'EMP003',
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'password' => Hash::make('password'),
                'user_type' => 'employee',
                'department_id' => 3,
                'position' => 'IT Specialist',
                'office' => 'Tech Wing',
                'salary' => 70000,
                'start_date' => '2021-05-10'
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
