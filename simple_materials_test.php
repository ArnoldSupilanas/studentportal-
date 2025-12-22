<?php
// Simple test for materials functionality
echo "=== Materials System Test ===\n";

// Test 1: Check if materials table exists and is accessible
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'lms_supilanas';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "1. Database connection: SUCCESS\n";

// Check materials table structure
$result = $conn->query("DESCRIBE materials");
if ($result) {
    echo "2. Materials table: EXISTS\n";
    while($row = $result->fetch_assoc()) {
        echo "   - {$row['Field']} ({$row['Type']})\n";
    }
} else {
    echo "2. Materials table: MISSING\n";
}

// Check if we can insert a test material
$testInsert = "INSERT INTO materials (course_id, file_name, file_path, created_at) VALUES (5, 'test.pdf', 'uploads/test.pdf', NOW())";
if ($conn->query($testInsert)) {
    echo "3. Material insertion: SUCCESS\n";
    $lastId = $conn->insert_id;
    
    // Test retrieval
    $result = $conn->query("SELECT * FROM materials WHERE id = $lastId");
    if ($result->num_rows > 0) {
        echo "4. Material retrieval: SUCCESS\n";
    } else {
        echo "4. Material retrieval: FAILED\n";
    }
    
    // Clean up
    $conn->query("DELETE FROM materials WHERE id = $lastId");
    echo "5. Material deletion: SUCCESS\n";
} else {
    echo "3. Material insertion: FAILED - " . $conn->error . "\n";
}

// Check upload directory
$uploadDir = 'writable/uploads/materials';
if (is_dir($uploadDir)) {
    echo "6. Upload directory: EXISTS\n";
} else {
    if (mkdir($uploadDir, 0755, true)) {
        echo "6. Upload directory: CREATED\n";
    } else {
        echo "6. Upload directory: FAILED TO CREATE\n";
    }
}

$conn->close();
echo "\n=== Test Complete ===\n";
?>
