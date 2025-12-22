<?php
// Check if courses exist in database
echo "Checking database for courses...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Check if courses exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM courses");
    $count = $stmt->fetchColumn();
    
    echo "Current courses count: $count\n";
    
    if ($count > 0) {
        // Show course details
        $stmt = $pdo->query("SELECT id, title, description, instructor_id, status FROM courses");
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\nCourses in database:\n";
        foreach ($courses as $course) {
            echo "- ID: {$course['id']}, Title: {$course['title']}, Status: {$course['status']}\n";
        }
    } else {
        echo "No courses found in database.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
