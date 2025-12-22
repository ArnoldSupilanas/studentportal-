<?php
// Debug course status in database
echo "Checking course status in database...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Check course status
    $stmt = $pdo->query("SELECT id, title, status FROM courses");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nCourse status in database:\n";
    foreach ($courses as $course) {
        echo "- ID: {$course['id']}, Title: {$course['title']}, Status: '{$course['status']}'\n";
    }
    
    // Check if getActiveCourses() method will work
    $activeCourses = array_filter($courses, function($course) {
        return $course['status'] === 'active';
    });
    
    echo "\nActive courses count: " . count($activeCourses) . "\n";
    
    if (count($activeCourses) > 0) {
        echo "getActiveCourses() method should work.\n";
    } else {
        echo "No active courses found - this is the problem!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
