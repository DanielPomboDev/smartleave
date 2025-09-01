<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\LeaveRecordSeeder;

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
            LeaveRecordSeeder::class,
        ]);
    }
}
