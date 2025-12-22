<?php
// Verify courses are displaying in dashboard
echo "Verifying available courses display...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Get active courses
    $stmt = $pdo->query("SELECT id, title, description, instructor_name, status FROM courses WHERE status = 'active'");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Active courses in database: " . count($courses) . "\n";
    
    if (count($courses) > 0) {
        echo "\nCourses available for enrollment:\n";
        foreach ($courses as $course) {
            echo "- {$course['title']} (Instructor: {$course['instructor_name']})\n";
        }
        echo "\n✅ Available courses section should display these courses with enroll buttons.\n";
        echo "✅ Dashboard should show course count: " . count($courses) . "\n";
    } else {
        echo "\n❌ No active courses found in database.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
