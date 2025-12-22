<?php
// Simple script to create materials table
require_once 'vendor/autoload.php';

// Initialize CodeIgniter
$app = new CodeIgniter\Boot();
$app->initialize();
$context = $app->getContext();
$db = \Config\Database::connect();

try {
    // Create materials table
    $sql = "
        CREATE TABLE IF NOT EXISTS materials (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            course_id INT UNSIGNED NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ";
    
    $db->query($sql);
    echo "Materials table created successfully!\n";
    
} catch (Exception $e) {
    echo "Error creating materials table: " . $e->getMessage() . "\n";
}
