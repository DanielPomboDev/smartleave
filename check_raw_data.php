<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check raw leave records for EMP001
$records = DB::table('leave_records')->where('user_id', 'EMP001')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

echo "RAW DATABASE RECORDS FOR EMP001:\n";
echo "Month/Year\tVacation Earned\tVacation Used\tVacation Balance\tSick Earned\tSick Used\tSick Balance\n";

foreach ($records as $record) {
    echo $record->month . "/" . $record->year . "\t" . 
         $record->vacation_earned . "\t\t" . $record->vacation_used . "\t\t" . $record->vacation_balance . "\t\t" .
         $record->sick_earned . "\t\t" . $record->sick_used . "\t\t" . $record->sick_balance . "\n";
}