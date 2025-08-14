<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE DATABASE IF NOT EXISTS smart_leave";
    $pdo->exec($sql);
    
    echo "Database 'smart_leave' created successfully or already exists.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
