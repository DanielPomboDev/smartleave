<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LeaveRecord;
use App\Models\User;

echo "Current Leave Records for EMP001:\n";
echo "================================\n";

$records = LeaveRecord::where('user_id', 'EMP001')
    ->orderBy('year', 'desc')
    ->orderBy('month', 'desc')
    ->get();

foreach ($records as $record) {
    echo "{$record->month}/{$record->year}:\n";
    echo "  Vacation Used: {$record->vacation_used}\n";
    echo "  Vacation Balance: {$record->vacation_balance}\n";
    echo "  Vacation Entries: " . count($record->vacation_entries ?? []) . "\n";
    
    if (!empty($record->vacation_entries)) {
        foreach ($record->vacation_entries as $entry) {
            echo "    - {$entry['start_date']} to {$entry['end_date']} ({$entry['days']} days)\n";
        }
    }
    echo "\n";
}