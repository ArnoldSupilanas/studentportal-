<?php
// Comprehensive debugging tool for enrollment system
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<!DOCTYPE html>
<html>
<head>
    <title>Enrollment System Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2><i class="fas fa-bug me-2"></i>Enrollment System Debug Tool</h2>';

// 1. Check Database Connection
echo '<div class="card mb-3">
    <div class="card-header bg-primary text-white">Database Connection</div>
    <div class="card-body">';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=lms_supilanas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<div class="alert alert-success"><i class="fas fa-check me-2"></i>Database connection: SUCCESS</div>';
    
    // Check tables exist and have data
    $tables = ['users', 'courses', 'enrollments'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<div class='alert alert-info'><strong>$table:</strong> {$result['count']} records</div>";
    }
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '</div></div>';

// 2. Check Session
echo '<div class="card mb-3">
    <div class="card-header bg-warning text-dark">Session Status</div>
    <div class="card-body">';

session_start();
echo '<div class="row">';
echo '<div class="col-md-6"><strong>Session ID:</strong> ' . session_id() . '</div>';
echo '<div class="col-md-6"><strong>Logged In:</strong> ' . (($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false) ? 'YES' : 'NO') . '</div>';
echo '<div class="col-md-6"><strong>User ID:</strong> ' . ($_SESSION['userID'] ?? $_SESSION['user_id'] ?? 'Not set') . '</div>';
echo '<div class="col-md-6"><strong>Role:</strong> ' . ($_SESSION['role'] ?? 'Not set') . '</div>';
echo '</div>';

if (!($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false)) {
    echo '<div class="alert alert-warning">You are not logged in. <a href="debug_login.php" class="btn btn-sm btn-primary">Login Here</a></div>';
}

echo '</div></div>';

// 3. Test Student Data
echo '<div class="card mb-3">
    <div class="card-header bg-info text-white">Student Data Test</div>
    <div class="card-body">';

try {
    $stmt = $pdo->query("SELECT id, first_name, last_name, email, role FROM users WHERE role = 'student' LIMIT 3");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<h6>Available Students:</h6>';
    foreach ($students as $student) {
        echo '<div class="border p-2 mb-2">';
        echo '<strong>ID:</strong> ' . $student['id'] . ' | ';
        echo '<strong>Name:</strong> ' . $student['first_name'] . ' ' . $student['last_name'] . ' | ';
        echo '<strong>Email:</strong> ' . $student['email'];
        echo '</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Error fetching students: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '</div></div>';

// 4. Test Course Data
echo '<div class="card mb-3">
    <div class="card-header bg-success text-white">Course Data Test</div>
    <div class="card-body">';

try {
    $stmt = $pdo->query("SELECT id, title, status FROM courses WHERE status = 'active' LIMIT 3");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<h6>Available Courses:</h6>';
    foreach ($courses as $course) {
        echo '<div class="border p-2 mb-2">';
        echo '<strong>ID:</strong> ' . $course['id'] . ' | ';
        echo '<strong>Title:</strong> ' . htmlspecialchars($course['title']) . ' | ';
        echo '<strong>Status:</strong> ' . $course['status'];
        echo '</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Error fetching courses: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '</div></div>';

// 5. Test Enrollment AJAX
echo '<div class="card mb-3">
    <div class="card-header bg-dark text-white">AJAX Endpoint Test</div>
    <div class="card-body">';

echo '<h6>Test Enrollment Endpoint:</h6>';
echo '<div class="row">';
echo '<div class="col-md-6">';
echo '<button onclick="testEnroll()" class="btn btn-primary">Test /course/enroll</button>';
echo '<div id="enroll-result" class="mt-2"></div>';
echo '</div>';
echo '<div class="col-md-6">';
echo '<button onclick="testUnenroll()" class="btn btn-danger">Test /course/unenroll</button>';
echo '<div id="unenroll-result" class="mt-2"></div>';
echo '</div>';
echo '</div>';

echo '</div></div>';

// 6. File System Check
echo '<div class="card mb-3">
    <div class="card-header bg-secondary text-white">File System Check</div>
    <div class="card-body">';

$files_to_check = [
    'app/Controllers/Course.php',
    'app/Controllers/Student.php',
    'app/Models/EnrollmentModel.php',
    'app/Models/CourseModel.php',
    'app/Views/student_dashboard.php',
    'app/Config/Routes.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo '<div class="alert alert-success"><i class="fas fa-check me-2"></i>' . $file . ' - EXISTS</div>';
    } else {
        echo '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>' . $file . ' - MISSING</div>';
    }
}

echo '</div></div>';

echo '</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function testEnroll() {
    $("#enroll-result").html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> Testing...");
    
    $.post("/course/enroll", {course_id: 1}, function(response) {
        $("#enroll-result").html("<div class=\"alert alert-success\">SUCCESS: " + JSON.stringify(response, null, 2) + "</div>");
    }, "json").fail(function(xhr) {
        $("#enroll-result").html("<div class=\"alert alert-danger\">ERROR " + xhr.status + ": " + xhr.responseText + "</div>");
    });
}

function testUnenroll() {
    $("#unenroll-result").html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> Testing...");
    
    $.post("/course/unenroll", {course_id: 1}, function(response) {
        $("#unenroll-result").html("<div class=\"alert alert-success\">SUCCESS: " + JSON.stringify(response, null, 2) + "</div>");
    }, "json").fail(function(xhr) {
        $("#unenroll-result").html("<div class=\"alert alert-danger\">ERROR " + xhr.status + ": " + xhr.responseText + "</div>");
    });
}
</script>

</body>
</html>';
?>
