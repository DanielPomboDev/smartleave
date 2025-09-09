<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearNotifications extends Command
{
    protected $signature = 'app:clear-notifications {--force : Force the operation to run without confirmation}';
    protected $description = 'Clear all notifications from the notifications table';

    public function handle()
    {
        // Ask for confirmation unless --force option is used
        if (!$this->option('force') && !$this->confirm('This will delete all data in the notifications table. Continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate the notifications table
            $this->info('Deleting data from notifications table...');
            DB::table('notifications')->truncate();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info('All notifications have been cleared successfully.');
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Error clearing notifications: ' . $e->getMessage());
        }
    }
}