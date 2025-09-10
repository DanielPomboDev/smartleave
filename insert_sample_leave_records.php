<?php

// Load Composer autoloader
require_once __DIR__.'/vendor/autoload.php';

// Load environment variables
if (file_exists(__DIR__.'/.env')) {
    $env = parse_ini_file(__DIR__.'/.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

// Database configuration
$config = [
    'driver'    => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port'      => $_ENV['DB_PORT'] ?? '3306',
    'database'  => $_ENV['DB_DATABASE'] ?? 'smart_leave',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];

try {
    // Create PDO connection
    $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if EMP001 exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute(['EMP001']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "User EMP001 not found in the database.\n";
        exit(1);
    }
    
    echo "Inserting sample leave records for EMP001 from January 2025 to September 2025...\n";
    
    // Sample data for EMP001
    $leaveRecords = [
        // January 2025 - Full attendance
        [
            'user_id' => 'EMP001',
            'month' => 1,
            'year' => 2025,
            'vacation_earned' => 1.250,
            'vacation_used' => 0,
            'vacation_balance' => 1.250, // Starting balance
            'sick_earned' => 1.250,
            'sick_used' => 0,
            'sick_balance' => 1.250, // Starting balance
            'undertime_hours' => 0,
            'lwop_days' => 0,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // February 2025 - Full attendance
        [
            'user_id' => 'EMP001',
            'month' => 2,
            'year' => 2025,
            'vacation_earned' => 1.250,
            'vacation_used' => 0,
            'vacation_balance' => 2.500, // Previous balance + earned
            'sick_earned' => 1.250,
            'sick_used' => 0,
            'sick_balance' => 2.500, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 0,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // March 2025 - Full attendance
        [
            'user_id' => 'EMP001',
            'month' => 3,
            'year' => 2025,
            'vacation_earned' => 1.250,
            'vacation_used' => 0,
            'vacation_balance' => 3.750, // Previous balance + earned
            'sick_earned' => 1.250,
            'sick_used' => 0,
            'sick_balance' => 3.750, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 0,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // April 2025 - 2 days LWOP
        [
            'user_id' => 'EMP001',
            'month' => 4,
            'year' => 2025,
            'vacation_earned' => 1.167, // Prorated for 28 days present (30-2)
            'vacation_used' => 0,
            'vacation_balance' => 4.917, // Previous balance + earned
            'sick_earned' => 1.167, // Prorated for 28 days present (30-2)
            'sick_used' => 0,
            'sick_balance' => 4.917, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 2,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // May 2025 - Full attendance
        [
            'user_id' => 'EMP001',
            'month' => 5,
            'year' => 2025,
            'vacation_earned' => 1.250,
            'vacation_used' => 0,
            'vacation_balance' => 6.167, // Previous balance + earned
            'sick_earned' => 1.250,
            'sick_used' => 0,
            'sick_balance' => 6.167, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 0,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // June 2025 - 1 day LWOP
        [
            'user_id' => 'EMP001',
            'month' => 6,
            'year' => 2025,
            'vacation_earned' => 1.208, // Prorated for 29 days present (30-1)
            'vacation_used' => 0,
            'vacation_balance' => 7.375, // Previous balance + earned
            'sick_earned' => 1.208, // Prorated for 29 days present (30-1)
            'sick_used' => 0,
            'sick_balance' => 7.375, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 1,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // July 2025 - Full attendance
        [
            'user_id' => 'EMP001',
            'month' => 7,
            'year' => 2025,
            'vacation_earned' => 1.250,
            'vacation_used' => 0,
            'vacation_balance' => 8.625, // Previous balance + earned
            'sick_earned' => 1.250,
            'sick_used' => 0,
            'sick_balance' => 8.625, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 0,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // August 2025 - 3 days LWOP
        [
            'user_id' => 'EMP001',
            'month' => 8,
            'year' => 2025,
            'vacation_earned' => 1.125, // Prorated for 27 days present (30-3)
            'vacation_used' => 0,
            'vacation_balance' => 9.750, // Previous balance + earned
            'sick_earned' => 1.125, // Prorated for 27 days present (30-3)
            'sick_used' => 0,
            'sick_balance' => 9.750, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 3,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ],
        // September 2025 - 5 days LWOP (as per your example)
        [
            'user_id' => 'EMP001',
            'month' => 9,
            'year' => 2025,
            'vacation_earned' => 1.042, // Prorated for 25 days present (30-5)
            'vacation_used' => 0,
            'vacation_balance' => 10.792, // Previous balance + earned
            'sick_earned' => 1.042, // Prorated for 25 days present (30-5)
            'sick_used' => 0,
            'sick_balance' => 10.792, // Previous balance + earned
            'undertime_hours' => 0,
            'lwop_days' => 5,
            'vacation_entries' => '[]',
            'sick_entries' => '[]'
        ]
    ];
    
    // Insert the leave records
    foreach ($leaveRecords as $record) {
        // Check if record already exists
        $stmt = $pdo->prepare("SELECT * FROM leave_records WHERE user_id = ? AND month = ? AND year = ?");
        $stmt->execute([$record['user_id'], $record['month'], $record['year']]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$exists) {
            $stmt = $pdo->prepare("
                INSERT INTO leave_records (
                    user_id, month, year, vacation_earned, vacation_used, vacation_balance,
                    sick_earned, sick_used, sick_balance, undertime_hours, lwop_days,
                    vacation_entries, sick_entries, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([
                $record['user_id'],
                $record['month'],
                $record['year'],
                $record['vacation_earned'],
                $record['vacation_used'],
                $record['vacation_balance'],
                $record['sick_earned'],
                $record['sick_used'],
                $record['sick_balance'],
                $record['undertime_hours'],
                $record['lwop_days'],
                $record['vacation_entries'],
                $record['sick_entries']
            ]);
            echo "Inserted leave record for {$record['year']}-{$record['month']}\n";
        } else {
            echo "Leave record for {$record['year']}-{$record['month']} already exists\n";
        }
    }
    
    echo "Sample leave records inserted successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}