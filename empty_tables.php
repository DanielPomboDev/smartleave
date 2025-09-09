<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Initialize Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Get all table names
    $tables = DB::select('SHOW TABLES');
    
    // Extract table names from the result
    $tableNames = [];
    foreach ($tables as $table) {
        $tableNames[] = array_values((array)$table)[0];
    }
    
    echo "Found " . count($tableNames) . " tables:\n";
    foreach ($tableNames as $tableName) {
        echo "- " . $tableName . "\n";
    }
    
    // Confirm before proceeding
    echo "\nAre you sure you want to empty all tables? This cannot be undone. (yes/no): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    
    if (trim(strtolower($line)) !== 'yes') {
        echo "Operation cancelled.\n";
        exit;
    }
    
    // Disable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Truncate each table
    foreach ($tableNames as $tableName) {
        echo "Emptying table: " . $tableName . "\n";
        DB::table($tableName)->truncate();
    }
    
    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    echo "All tables have been emptied successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}