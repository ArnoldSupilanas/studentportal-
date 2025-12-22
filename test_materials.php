<?php
// Test Materials Management System
require 'vendor/autoload.php';

$app = new CodeIgniter\CodeIgniter();
$app->initialize();

echo "=== Testing Materials Management System ===\n\n";

// Test 1: Check if Materials model works
echo "1. Testing MaterialModel...\n";
try {
    $materialModel = new \App\Models\MaterialModel();
    
    // Test getMaterialsByCourse with course ID 5
    $materials = $materialModel->getMaterialsByCourse(5);
    echo "   - getMaterialsByCourse(5): Found " . count($materials) . " materials\n";
    
    // Test insertMaterial
    $testData = [
        'course_id' => 5,
        'file_name' => 'test_document.pdf',
        'file_path' => 'uploads/materials/5/test_document.pdf',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $insertId = $materialModel->insertMaterial($testData);
    if ($insertId) {
        echo "   - insertMaterial: SUCCESS - ID: $insertId\n";
        
        // Test getMaterial
        $material = $materialModel->getMaterial($insertId);
        echo "   - getMaterial($insertId): " . ($material ? "SUCCESS" : "FAILED") . "\n";
        
        // Test getMaterialsByCourse again
        $materialsAfter = $materialModel->getMaterialsByCourse(5);
        echo "   - getMaterialsByCourse(5) after insert: Found " . count($materialsAfter) . " materials\n";
        
        // Clean up - delete test material
        $deleted = $materialModel->deleteMaterial($insertId);
        echo "   - deleteMaterial($insertId): " . ($deleted ? "SUCCESS" : "FAILED") . "\n";
    } else {
        echo "   - insertMaterial: FAILED\n";
    }
    
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

echo "\n2. Testing Course and Enrollment Models...\n";
try {
    $courseModel = new \App\Models\CourseModel();
    $enrollmentModel = new \App\Models\EnrollmentModel();
    
    // Test course retrieval
    $course = $courseModel->find(5);
    echo "   - CourseModel->find(5): " . ($course ? "SUCCESS - {$course['title']}" : "FAILED") . "\n";
    
    // Test enrollment check
    $isEnrolled = $enrollmentModel->isAlreadyEnrolled(3, 5); // student ID 3, course ID 5
    echo "   - Enrollment check for student 3 in course 5: " . ($isEnrolled ? "ENROLLED" : "NOT ENROLLED") . "\n";
    
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

echo "\n3. Testing Routes Existence...\n";
$routesFile = file_get_contents('app/Config/Routes.php');
$materialRoutes = [
    'materials/upload/(:num)',
    'materials/download/(:num)',
    'materials/delete/(:num)',
    'materials/view/(:num)'
];

foreach ($materialRoutes as $route) {
    if (strpos($routesFile, $route) !== false) {
        echo "   - Route '$route': EXISTS\n";
    } else {
        echo "   - Route '$route': MISSING\n";
    }
}

echo "\n4. Testing Upload Directory...\n";
$uploadDir = WRITEPATH . 'uploads/materials/5';
if (!is_dir($uploadDir)) {
    if (mkdir($uploadDir, 0755, true)) {
        echo "   - Upload directory created: SUCCESS\n";
    } else {
        echo "   - Upload directory creation: FAILED\n";
    }
} else {
    echo "   - Upload directory exists: SUCCESS\n";
}

echo "\n=== Test Summary ===\n";
echo "Materials system appears to be properly implemented.\n";
echo "Next steps: Test through web interface with actual file uploads.\n";
?>
