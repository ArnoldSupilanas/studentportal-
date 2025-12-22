<?php
// Quick and simple course addition
echo "Adding courses to database...\n";

try {
    // Connect to database using PDO
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Check if courses exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM courses");
    $count = $stmt->fetchColumn();
    
    echo "Current courses count: $count\n";
    
    if ($count == 0) {
        // Insert test courses
        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn fundamentals of web development including HTML, CSS, and JavaScript basics',
                'instructor_id' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Database Design and Management',
                'description' => 'Comprehensive guide to database design principles and SQL',
                'instructor_id' => 2,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Advanced JavaScript Programming',
                'description' => 'Master modern JavaScript concepts including ES6+, async programming, and frameworks',
                'instructor_id' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'PHP Web Development',
                'description' => 'Build dynamic web applications using PHP and modern frameworks',
                'instructor_id' => 2,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Insert courses
        $insert = $pdo->prepare("INSERT INTO courses (title, description, instructor_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($courses as $course) {
            $insert->execute([
                $course['title'],
                $course['description'],
                $course['instructor_id'],
                $course['status'],
                $course['created_at'],
                $course['updated_at']
            ]);
        }
        
        echo "Created " . count($courses) . " courses successfully!\n";
        echo "Courses added to database. Dashboard should now show available courses.\n";
    } else {
        echo "Courses already exist in database.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
