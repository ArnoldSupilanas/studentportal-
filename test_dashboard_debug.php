<?php
require_once 'vendor/autoload.php';

// Test database connection
try {
    $db = \Config\Database::connect();
    echo "Database connection: " . ($db->connID ? "SUCCESS" : "FAILED") . "\n";
    
    // Test if enrollments table exists
    $result = $db->query("SHOW TABLES LIKE 'enrollments'");
    $tableExists = $result->getNumRows() > 0;
    echo "Enrollments table exists: " . ($tableExists ? "YES" : "NO") . "\n";
    
    if ($tableExists) {
        // Test enrollment model
        $enrollmentModel = new \App\Models\EnrollmentModel();
        echo "EnrollmentModel loaded: SUCCESS\n";
        
        // Test course model
        $courseModel = new \App\Models\CourseModel();
        echo "CourseModel loaded: SUCCESS\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
