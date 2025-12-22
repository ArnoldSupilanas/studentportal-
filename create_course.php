<?php
// Create database connection
$db = new mysqli('localhost', 'root', '', 'lms_supilanas');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create Course 1 if it doesn't exist
$result = $db->query("SELECT id FROM courses WHERE id = 1");
if ($result->num_rows == 0) {
    $sql = "INSERT INTO courses (id, title, description, course_code, instructor_id, status, created_at, updated_at) VALUES (1, 'Course 1', 'Test course description', 'COURSE1', 2, 'published', NOW(), NOW())";
    
    if ($db->query($sql)) {
        echo "Course 1 created successfully\n";
    } else {
        echo "Error creating course 1: " . $db->error . "\n";
    }
} else {
    echo "Course 1 already exists\n";
}

// Create Course 2 if it doesn't exist
$result = $db->query("SELECT id FROM courses WHERE id = 2");
if ($result->num_rows == 0) {
    $sql = "INSERT INTO courses (id, title, description, course_code, instructor_id, status, created_at, updated_at) VALUES (2, 'Course 2', 'Test course description', 'COURSE2', 2, 'published', NOW(), NOW())";
    
    if ($db->query($sql)) {
        echo "Course 2 created successfully\n";
    } else {
        echo "Error creating course 2: " . $db->error . "\n";
    }
} else {
    echo "Course 2 already exists\n";
}

// Create Course 3 if it doesn't exist
$result = $db->query("SELECT id FROM courses WHERE id = 3");
if ($result->num_rows == 0) {
    $sql = "INSERT INTO courses (id, title, description, course_code, instructor_id, status, created_at, updated_at) VALUES (3, 'Course 3', 'Test course description', 'COURSE3', 2, 'published', NOW(), NOW())";
    
    if ($db->query($sql)) {
        echo "Course 3 created successfully\n";
    } else {
        echo "Error creating course 3: " . $db->error . "\n";
    }
} else {
    echo "Course 3 already exists\n";
}

$db->close();
?>
