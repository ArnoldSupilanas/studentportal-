<?php
// Add sample courses to database

// Database connection details
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_supilanas';
$port = 3306;

// Connect to database
$connection = mysqli_connect($hostname, $username, $password, $database, $port);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sampleCourses = [
    [
        'title' => 'Introduction to Computer Science',
        'description' => 'CS101',
        'instructor_id' => 1,
        'status' => 'active'
    ],
    [
        'title' => 'Web Development Fundamentals',
        'description' => 'WEB201',
        'instructor_id' => 1,
        'status' => 'active'
    ],
    [
        'title' => 'Database Management Systems',
        'description' => 'DB301',
        'instructor_id' => 1,
        'status' => 'active'
    ],
    [
        'title' => 'Advanced Programming',
        'description' => 'CS401',
        'instructor_id' => 1,
        'status' => 'active'
    ],
    [
        'title' => 'Network Security',
        'description' => 'SEC501',
        'instructor_id' => 1,
        'status' => 'inactive'
    ]
];

echo "Adding sample courses...\n";

foreach ($sampleCourses as $course) {
    try {
        // Check if course already exists
        $checkQuery = "SELECT id FROM courses WHERE title = '" . mysqli_real_escape_string($connection, $course['title']) . "'";
        $result = mysqli_query($connection, $checkQuery);
        
        if (mysqli_num_rows($result) == 0) {
            // Insert new course
            $insertQuery = "INSERT INTO courses (title, description, instructor_id, status, created_at, updated_at) VALUES (
                '" . mysqli_real_escape_string($connection, $course['title']) . "',
                '" . mysqli_real_escape_string($connection, $course['description']) . "',
                " . $course['instructor_id'] . ",
                '" . mysqli_real_escape_string($connection, $course['status']) . "',
                NOW(),
                NOW()
            )";
            
            if (mysqli_query($connection, $insertQuery)) {
                echo "Added: " . $course['title'] . "\n";
            } else {
                echo "Error adding " . $course['title'] . ": " . mysqli_error($connection) . "\n";
            }
        } else {
            echo "Already exists: " . $course['title'] . "\n";
        }
    } catch (Exception $e) {
        echo "Error adding " . $course['title'] . ": " . $e->getMessage() . "\n";
    }
}

mysqli_close($connection);
echo "Sample courses added successfully!\n";
