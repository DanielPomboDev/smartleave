<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Truncate all relevant tables
        DB::table('leave_approvals')->truncate();
        DB::table('leave_recommendations')->truncate();
        DB::table('leave_requests')->truncate();
        DB::table('users')->truncate();
        DB::table('departments')->truncate();
        DB::table('cache')->truncate();
        DB::table('cache_locks')->truncate();
        DB::table('failed_jobs')->truncate();
        DB::table('jobs')->truncate();
        DB::table('job_batches')->truncate();
        DB::table('password_reset_tokens')->truncate();
        DB::table('sessions')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do when rolling back
    }
};
