<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['name' => 'Accounting Office', 'description' => 'Handles financial transactions and reporting'],
            ['name' => 'Human Resources', 'description' => 'Manages employee relations and benefits'],
            ['name' => 'Information Technology', 'description' => 'Manages IT infrastructure and support'],
            ['name' => 'Marketing', 'description' => 'Handles marketing and communications'],
            ['name' => 'Operations', 'description' => 'Manages day-to-day operations'],
            ['name' => 'Sales', 'description' => 'Handles sales and customer relations'],
            ['name' => 'Legal', 'description' => 'Handles legal matters and compliance']
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert($department);
        }
    }
}
