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
        
        // Get all table names
        $tables = DB::select('SHOW TABLES');
        $tableNameKey = 'Tables_in_' . env('DB_DATABASE', 'smart_leave');
        
        // Truncate each table
        foreach ($tables as $table) {
            $tableName = $table->$tableNameKey;
            // Skip the migrations table as it's needed to track migrations
            if ($tableName !== 'migrations') {
                DB::table($tableName)->truncate();
            }
        }
        
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
