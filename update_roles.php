<?php

// Simple database connection script
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'lms_supilanas';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    echo "Updating user roles...\n\n";

    // Update instructor to teacher
    $stmt = $conn->prepare("UPDATE users SET role = 'teacher' WHERE role = 'instructor'");
    $stmt->execute();
    
    echo "Updated " . $stmt->affected_rows . " rows from 'instructor' to 'teacher'\n\n";

    // Display current users
    echo "Current users:\n";
    echo "+----+------------+----------------+----------+--------+\n";
    echo "| ID | Name       | Email          | Role     | Status |\n";
    echo "+----+------------+----------------+----------+--------+\n";
    
    $result = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) as name, email, role, status FROM users ORDER BY id");
    while ($row = $result->fetch_assoc()) {
        printf("| %-2s | %-10s | %-14s | %-8s | %-6s |\n", 
            $row['id'], 
            substr($row['name'], 0, 10), 
            substr($row['email'], 0, 14), 
            $row['role'],
            $row['status']
        );
    }
    echo "+----+------------+----------------+----------+--------+\n";

    echo "\n✅ Role update complete!\n";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
