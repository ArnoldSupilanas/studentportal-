<?php
// Add material to Introduction to Computer Science course
$db = new mysqli('localhost', 'root', '', 'lms_supilanas');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Insert material for course ID 5 (Introduction to Computer Science)
$sql = "INSERT INTO materials (course_id, file_name, file_path, created_at) VALUES (5, 'Introduction to Computer Science Materials.txt', 'uploads/materials/5/intro_cs_materials.txt', NOW())";

if ($db->query($sql)) {
    echo "Material added to Introduction to Computer Science course successfully\n";
    echo "Material ID: " . $db->insert_id . "\n";
} else {
    echo "Error adding material: " . $db->error . "\n";
}

$db->close();
?>
