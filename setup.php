<?php
require_once 'config/config.php';

try {
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS attendance_system");
    $pdo->exec("USE attendance_system");

    // Run the schema
    $schema = file_get_contents('database/schema.sql');

    // Remove the CREATE DATABASE and USE statements since we're already connected
    $schema = preg_replace('/CREATE DATABASE.*;/', '', $schema);
    $schema = preg_replace('/USE.*;/', '', $schema);

    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }

    echo "Database setup completed successfully!\n";

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage() . "\n");
}
?>