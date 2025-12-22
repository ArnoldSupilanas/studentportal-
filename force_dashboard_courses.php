<?php
// Force dashboard to use test courses data
echo "Forcing dashboard to use test courses data...\n";

try {
    // Read the test courses file
    $testCoursesFile = 'app/Views/auth/test_courses.php';
    if (file_exists($testCoursesFile)) {
        include $testCoursesFile;
        echo "Test courses data loaded successfully.\n";
        echo "Dashboard should now display the 4 test courses.\n";
    } else {
        echo "Test courses file not found.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
