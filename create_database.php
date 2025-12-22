<?php
// Create database first
echo "Creating database...\n";

try {
    // Connect to MySQL without specifying database
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS ite311_supilanas");
    
    echo "Database created successfully!\n";
    
    // Connect to the created database
    $pdo->exec("USE ite311_supilanas");
    
    // Create courses table
    $createTable = "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        instructor_id INT,
        status ENUM('draft','active','archived') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($pdo->exec($createTable)) {
        echo "Courses table created successfully!\n";
    } else {
        echo "Error creating courses table\n";
    }
    
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
    echo "Refresh your dashboard to see available courses.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
