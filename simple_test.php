<?php
// Simple test to verify database has real data
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_supilanas';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "Database connected successfully!\n\n";
    
    // Test courses table
    echo "Testing courses table:\n";
    $result = $conn->query("SELECT COUNT(*) as count FROM courses");
    $row = $result->fetch_assoc();
    echo "   Total courses: " . $row['count'] . "\n";
    
    $result = $conn->query("SELECT id, title, status FROM courses LIMIT 5");
    while ($row = $result->fetch_assoc()) {
        echo "   - [{$row['id']}] {$row['title']} ({$row['status']})\n";
    }
    
    // Test users table
    echo "\nTesting users table:\n";
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $result->fetch_assoc();
    echo "   Total users: " . $row['count'] . "\n";
    
    $result = $conn->query("SELECT id, first_name, last_name, role FROM users LIMIT 5");
    while ($row = $result->fetch_assoc()) {
        echo "   - [{$row['id']}] {$row['first_name']} {$row['last_name']} ({$row['role']})\n";
    }
    
    // Test enrollments table
    echo "\nTesting enrollments table:\n";
    $result = $conn->query("SELECT COUNT(*) as count FROM enrollments");
    $row = $result->fetch_assoc();
    echo "   Total enrollments: " . $row['count'] . "\n";
    
    $result = $conn->query("SELECT e.id, c.title as course_name, u.first_name, u.last_name, e.status FROM enrollments e JOIN courses c ON e.course_id = c.id JOIN users u ON e.user_id = u.id LIMIT 5");
    while ($row = $result->fetch_assoc()) {
        echo "   - [{$row['id']}] {$row['first_name']} {$row['last_name']} enrolled in {$row['course_name']} ({$row['status']})\n";
    }
    
    echo "\nDatabase test completed successfully!\n";
    echo "Course management system now has real data!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
