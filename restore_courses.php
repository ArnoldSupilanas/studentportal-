<?php
// Direct database connection to create test courses
echo "Connecting to database to restore available courses...\n";

try {
    // Direct MySQL connection
    $mysqli = new mysqli('localhost', 'root', '', 'ite311_supilanas');
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    echo "Connected successfully!\n";
    
    // Check existing courses
    $result = $mysqli->query("SELECT COUNT(*) FROM courses");
    $row = $result->fetch_row();
    $count = $row[0];
    
    echo "Current courses count: $count\n";
    
    if ($count == 0) {
        // Create test courses
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
        $stmt = $mysqli->prepare("INSERT INTO courses (title, description, instructor_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($courses as $course) {
            $stmt->bind_param("sssis", 
                $course['title'], 
                $course['description'], 
                $course['instructor_id'], 
                $course['status'], 
                $course['created_at'], 
                $course['updated_at']
            );
            $stmt->execute();
        }
        
        echo "Created " . count($courses) . " test courses successfully!\n";
    } else {
        echo "Courses already exist in database.\n";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
