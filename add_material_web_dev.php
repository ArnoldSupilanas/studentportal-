<?php
// Add material to Web Development Fundamentals course
$db = new mysqli('localhost', 'root', '', 'lms_supilanas');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Insert material for course ID 6 (Web Development Fundamentals)
$sql = "INSERT INTO materials (course_id, file_name, file_path, created_at) VALUES (6, 'Web Development Fundamentals Materials.txt', 'uploads/materials/6/web_dev_materials.txt', NOW())";

if ($db->query($sql)) {
    echo "Material added to Web Development Fundamentals course successfully\n";
    echo "Material ID: " . $db->insert_id . "\n";
} else {
    echo "Error adding material: " . $db->error . "\n";
}

$db->close();
?>
