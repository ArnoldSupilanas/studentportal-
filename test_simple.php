<?php
// Simple test to check if the application loads
try {
    // Test basic CodeIgniter loading
    $app = new \CodeIgniter\CodeIgniter();
    echo "✅ CodeIgniter loaded successfully\n";
    
    // Test database connection
    $db = \Config\Database::connect();
    if ($db->connID) {
        echo "✅ Database connected successfully\n";
    } else {
        echo "❌ Database connection failed\n";
    }
    
    // Test models
    $enrollmentModel = new \App\Models\EnrollmentModel();
    echo "✅ EnrollmentModel loaded\n";
    
    $courseModel = new \App\Models\CourseModel();
    echo "✅ CourseModel loaded\n";
    
    // Test Auth controller
    $auth = new \App\Controllers\Auth();
    echo "✅ Auth controller loaded\n";
    
    echo "\n🎉 All components loaded successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
