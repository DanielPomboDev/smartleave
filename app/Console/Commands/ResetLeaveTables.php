<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ResetLeaveTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-leave-tables {--force : Force the operation to run without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset leave requests, recommendations, approvals tables and reseed leave records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ask for confirmation unless --force option is used
        if (!$this->option('force') && !$this->confirm('This will delete all data in leave_requests, leave_recommendations, and leave_approvals tables. Continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate the tables in the correct order to avoid foreign key issues
            $this->info('Deleting data from leave_approvals table...');
            DB::table('leave_approvals')->truncate();

            $this->info('Deleting data from leave_recommendations table...');
            DB::table('leave_recommendations')->truncate();

            $this->info('Deleting data from leave_requests table...');
            DB::table('leave_requests')->truncate();

            $this->info('Deleting data from leave_records table...');
            DB::table('leave_records')->truncate();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info('All leave tables have been cleared successfully.');

            // Run the LeaveRecordSeeder
            $this->info('Seeding leave records...');
            Artisan::call('db:seed', ['--class' => 'LeaveRecordSeeder']);
            $this->info('Leave records seeded successfully.');

            $this->info('All tables have been reset and reseeded successfully!');
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Error resetting tables: ' . $e->getMessage());
        }
    }
}
