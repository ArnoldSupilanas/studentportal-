<?php
// Add materials to all main courses
$db = new mysqli('localhost', 'root', '', 'lms_supilanas');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$courses = [
    7 => 'Database Management Systems',
    8 => 'Advanced Programming', 
    9 => 'Network Security',
    19 => 'Introduction to Web Development',
    20 => 'Database Design and Management',
    21 => 'PHP Programming Fundamentals'
];

foreach ($courses as $course_id => $course_title) {
    // Check if material already exists
    $result = $db->query("SELECT id FROM materials WHERE course_id = $course_id");
    if ($result->num_rows == 0) {
        $file_name = $course_title . ' Materials.txt';
        $file_path = 'uploads/materials/' . $course_id . '/' . strtolower(str_replace(' ', '_', $course_title)) . '_materials.txt';
        
        $sql = "INSERT INTO materials (course_id, file_name, file_path, created_at) VALUES ($course_id, '$file_name', '$file_path', NOW())";
        
        if ($db->query($sql)) {
            echo "Material added to $course_title (ID: $course_id) - Material ID: " . $db->insert_id . "\n";
            
            // Create directory and file
            $dir = "writable/uploads/materials/$course_id";
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            $content = "$course_title - Course Materials\n";
            $content .= "================================\n\n";
            $content .= "This is course material for $course_title.\n";
            $content .= "Created: " . date('Y-m-d H:i:s') . "\n\n";
            $content .= "Course Topics:\n";
            $content .= "- Fundamental concepts\n";
            $content .= "- Practical applications\n";
            $content .= "- Hands-on exercises\n";
            $content .= "- Final projects\n\n";
            $content .= "Instructor: System Admin\n";
            $content .= "Contact: admin@lms.com\n";
            
            file_put_contents("$dir/" . basename($file_path), $content);
            echo "Physical file created for $course_title\n";
        } else {
            echo "Error adding material to $course_title: " . $db->error . "\n";
        }
    } else {
        echo "Material already exists for $course_title (ID: $course_id)\n";
    }
}

echo "\nAll materials added successfully!\n";
$db->close();
?>
