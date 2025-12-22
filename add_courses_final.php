<?php
// Add courses using the same database config as the application
echo "Adding courses to restore available courses section...\n";

try {
    // Use the database configuration from the app
    // Use DSN that matches the app's database configuration
    $dsn = 'mysql:host=localhost;dbname=lms_supilanas;charset=utf8mb4';
    $user = 'root';
    $pass = '';
    $pdo = new \PDO($dsn, $user, $pass, [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ]);
    
    // Check if courses exist and how many are available
    $check = $pdo->query("SELECT COUNT(*) AS cnt FROM courses");
    $count = (int) (($check->fetch()['cnt'] ?? 0));
    $availableCheck = $pdo->query("SELECT COUNT(*) AS cnt FROM courses WHERE status IN ('published','active')");
    $availableCount = (int) (($availableCheck->fetch()['cnt'] ?? 0));
    
    echo "Total courses in DB: $count\n";
    echo "Currently available courses: $availableCount\n";
    
    if ($availableCount == 0) {
        // Insert test courses
        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn fundamentals of web development including HTML, CSS, and JavaScript basics',
                'instructor_id' => 1,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Database Design and Management',
                'description' => 'Comprehensive guide to database design principles and SQL',
                'instructor_id' => 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Advanced JavaScript Programming',
                'description' => 'Master modern JavaScript concepts including ES6+, async programming, and frameworks',
                'instructor_id' => 1,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'PHP Web Development',
                'description' => 'Build dynamic web applications using PHP and modern frameworks',
                'instructor_id' => 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Insert courses using the database connection
        $insert = $pdo->prepare(
            "INSERT INTO courses (title, description, instructor_id, status, created_at, updated_at)
             VALUES (:title, :description, :instructor_id, :status, :created_at, :updated_at)"
        );
        foreach ($courses as $course) {
            $insert->execute($course);
        }
        
        echo "Added " . count($courses) . " courses successfully!\n";
        echo "Refresh your dashboard to see available courses.\n";
    } else {
        echo "Courses already exist in database.\n";
    }
    
    // Rename first five available courses to requested titles
    $desiredTitles = [
        'Introduction to Computer Science',
        'Web Development Fundamentals',
        'Database Management Systems',
        'Advanced Programming',
        'Network Security'
    ];
    $stmt = $pdo->query("SELECT id, title FROM courses WHERE status IN ('published','active') ORDER BY id ASC LIMIT 5");
    $rows = $stmt->fetchAll();
    $limit = min(count($rows), count($desiredTitles));
    $upd = $pdo->prepare("UPDATE courses SET title = :title, updated_at = :updated_at WHERE id = :id");
    for ($i = 0; $i < $limit; $i++) {
        $upd->execute([':title' => $desiredTitles[$i], ':updated_at' => date('Y-m-d H:i:s'), ':id' => $rows[$i]['id']]);
        echo "Renamed course ID {$rows[$i]['id']} to '{$desiredTitles[$i]}'\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
