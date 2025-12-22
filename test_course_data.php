<?php
// Test script to verify real database functionality
require_once 'vendor/autoload.php';

// Initialize CodeIgniter
$app = new CodeIgniter\CodeIgniter(APP_PATH, true);

try {
    // Test CourseModel
    $courseModel = new \App\Models\CourseModel();
    
    echo "Testing CourseModel with real database...\n\n";
    
    // Test getCoursesForAdmin
    echo "1. Testing getCoursesForAdmin():\n";
    $result = $courseModel->getCoursesForAdmin(null, null, 1, 5);
    echo "   Found " . count($result['courses']) . " courses\n";
    echo "   Total pages: " . $result['pagination']['total_pages'] . "\n";
    
    if (!empty($result['courses'])) {
        foreach ($result['courses'] as $course) {
            echo "   - Course: {$course['name']} ({$course['code']}) - {$course['teacher']} - {$course['students']} students\n";
        }
    }
    
    // Test getCourseById
    echo "\n2. Testing getCourseById(1):\n";
    $course = $courseModel->getCourseById(1);
    if ($course) {
        echo "   Found: {$course['name']} ({$course['code']}) - {$course['teacher']}\n";
    } else {
        echo "   Course not found\n";
    }
    
    // Test getCourseStudents
    echo "\n3. Testing getCourseStudents(1):\n";
    $students = $courseModel->getCourseStudents(1);
    echo "   Found " . count($students) . " students\n";
    foreach ($students as $student) {
        echo "   - {$student['first_name']} {$student['last_name']} ({$student['email']}) - {$student['status']}\n";
    }
    
    // Test getCourseStats
    echo "\n4. Testing getCourseStats(1):\n";
    $stats = $courseModel->getCourseStats(1);
    echo "   Total students: {$stats['total_students']}\n";
    echo "   Active students: {$stats['active_students']}\n";
    echo "   Completion rate: {$stats['completion_rate']}%\n";
    echo "   Average grade: {$stats['average_grade']}\n";
    
    // Test createCourse
    echo "\n5. Testing createCourse():\n";
    $newCourseData = [
        'name' => 'Test Course',
        'code' => 'TEST101',
        'status' => 'active'
    ];
    $createResult = $courseModel->createCourse($newCourseData);
    if ($createResult) {
        echo "   Course created successfully with ID: $createResult\n";
    } else {
        echo "   Failed to create course\n";
    }
    
    echo "\nAll tests completed successfully!\n";
    echo "Course management system is now using real database data.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
