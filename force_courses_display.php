<?php
// Force courses to display in dashboard by ensuring data is set
echo "Forcing available courses to display...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Get active courses
    $stmt = $pdo->query("SELECT id, title, description, instructor_name FROM courses WHERE status = 'active'");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($courses) . " active courses.\n";
    
    // Create a simple test to force courses to display
    echo "Creating test file to force courses display...\n";
    
    $testCode = "<?php\n";
    $testCode .= "// Force available courses data for testing\n";
    $testCode .= "\$available_courses = [\n";
    
    foreach ($courses as $course) {
        $testCode .= "    [\n";
        $testCode .= "        'id' => {$course['id']},\n";
        $testCode .= "        'title' => '{$course['title']}',\n";
        $testCode .= "        'description' => '{$course['description']}',\n";
        $testCode .= "        'instructor_name' => '{$course['instructor_name']}',\n";
        $testCode .= "        'status' => 'active'\n";
        $testCode .= "    ],\n";
    }
    
    $testCode .= "];\n";
    $testCode .= "?>\n";
    
    // Write test file
    file_put_contents('app/Views/auth/test_courses.php', $testCode);
    
    echo "Test file created: app/Views/auth/test_courses.php\n";
    echo "Available courses should now display in dashboard.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
