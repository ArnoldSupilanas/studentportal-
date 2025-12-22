<?php
// Check database table structure
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_supilanas';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    echo "Checking table structures...\n\n";
    
    // Check courses table structure
    echo "Courses table structure:\n";
    $result = $conn->query("DESCRIBE courses");
    while ($row = $result->fetch_assoc()) {
        echo "   - {$row['Field']} ({$row['Type']})\n";
    }
    
    echo "\nUsers table structure:\n";
    $result = $conn->query("DESCRIBE users");
    while ($row = $result->fetch_assoc()) {
        echo "   - {$row['Field']} ({$row['Type']})\n";
    }
    
    echo "\nEnrollments table structure:\n";
    $result = $conn->query("DESCRIBE enrollments");
    while ($row = $result->fetch_assoc()) {
        echo "   - {$row['Field']} ({$row['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
