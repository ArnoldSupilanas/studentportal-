<?php
// Check materials in database
$db = new mysqli('localhost', 'root', '', 'lms_supilanas');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "=== Materials in database ===\n";
$result = $db->query("SELECT * FROM materials ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " | Course: " . $row["course_id"] . " | File: " . $row["file_name"] . " | Created: " . $row["created_at"] . "\n";
    }
} else {
    echo "No materials found in database\n";
}

echo "\n=== Courses in database ===\n";
$result = $db->query("SELECT * FROM courses ORDER BY id");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " | Title: " . $row["title"] . " | Code: " . $row["course_code"] . "\n";
    }
} else {
    echo "No courses found in database\n";
}

$db->close();
?>
