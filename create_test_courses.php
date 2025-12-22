<?php
// Create test courses to restore available courses section
$db = \Config\Database::connect();

// Create test courses if table is empty
$checkCourses = $database->query("SELECT COUNT(*) as count FROM courses WHERE status = 'active'");
$result = $checkCourses->getRow();

if ($result->count == 0) {
    echo "Creating test courses...\n";
    
    // Insert test courses
    $testCourses = [
        [
            'title' => 'Introduction to Web Development',
            'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript basics',
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
    
    foreach ($testCourses as $course) {
        $database->table('courses')->insert($course);
    }
    
    echo "Created " . count($testCourses) . " test courses successfully!\n";
} else {
    echo "Found " . $result->count . " active courses in database.\n";
}

?>
