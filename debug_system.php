<?php
// Comprehensive debugging tool for the enrollment system
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<!DOCTYPE html>
<html>
<head>
    <title>Enrollment System Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1><i class="fas fa-bug me-2"></i>Enrollment System Debug Tool</h1>';

// 1. Check Server Status
echo '<div class="card mb-3">
    <div class="card-header bg-primary text-white">Server Status</div>
    <div class="card-body">';

$serverRunning = false;
$ch = curl_init('http://localhost:8080');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode === 200) {
    $serverRunning = true;
    echo '<div class="alert alert-success"><i class="fas fa-check me-2"></i>CodeIgniter server is RUNNING on port 8080</div>';
} else {
    echo '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>CodeIgniter server is NOT responding</div>';
}
curl_close($ch);

echo '</div></div>';

// 2. Database Connection Test
echo '<div class="card mb-3">
    <div class="card-header bg-success text-white">Database Connection</div>
    <div class="card-body">';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=lms_supilanas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<div class="alert alert-success"><i class="fas fa-check me-2"></i>Database connection: SUCCESS</div>';
    
    // Check tables and data
    $tables = ['users', 'courses', 'enrollments'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<div class='alert alert-info'><strong>$table:</strong> {$result['count']} records</div>";
    }
    
    // Show sample data
    echo '<h6>Sample Students:</h6>';
    $stmt = $pdo->query("SELECT id, first_name, last_name, email, role FROM users WHERE role = 'student' LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="border p-2 mb-2">';
        echo "<strong>ID:</strong> {$row['id']} | ";
        echo "<strong>Name:</strong> {$row['first_name']} {$row['last_name']} | ";
        echo "<strong>Email:</strong> {$row['email']}";
        echo '</div>';
    }
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '</div></div>';

// 3. Route Testing
echo '<div class="card mb-3">
    <div class="card-header bg-warning text-dark">Route Testing</div>
    <div class="card-body">';

$routes = [
    '/quick-login' => 'Quick Login Page',
    '/enrollment-dashboard' => 'Enrollment Dashboard',
    '/course/enroll' => 'Course Enrollment (POST)',
    '/course/unenroll' => 'Course Unenrollment (POST)'
];

foreach ($routes as $route => $description) {
    $ch = curl_init('http://localhost:8080' . $route);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode === 200) {
        echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>$route - $description: <strong>OK</strong></div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>$route - $description: <strong>ERROR $httpCode</strong></div>";
    }
    curl_close($ch);
}

echo '</div></div>';

// 4. Session Test
echo '<div class="card mb-3">
    <div class="card-header bg-info text-white">Session Status</div>
    <div class="card-body">';

session_start();
echo '<div class="row">';
echo '<div class="col-md-6"><strong>Session ID:</strong> ' . session_id() . '</div>';
echo '<div class="col-md-6"><strong>Logged In:</strong> ' . (($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false) ? 'YES' : 'NO') . '</div>';
echo '<div class="col-md-6"><strong>User ID:</strong> ' . ($_SESSION['userID'] ?? $_SESSION['user_id'] ?? 'Not set') . '</div>';
echo '<div class="col-md-6"><strong>Role:</strong> ' . ($_SESSION['role'] ?? 'Not set') . '</div>';
echo '</div>';

if (!($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false)) {
    echo '<div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        You are not logged in. 
        <a href="http://localhost:8080/quick-login" class="btn btn-sm btn-primary ms-2">Login Here</a>
    </div>';
} else {
    echo '<div class="alert alert-success">
        <i class="fas fa-check me-2"></i>
        You are logged in! 
        <a href="http://localhost:8080/enrollment-dashboard" class="btn btn-sm btn-success ms-2">Go to Dashboard</a>
    </div>';
}

echo '</div></div>';

// 5. AJAX Test
echo '<div class="card mb-3">
    <div class="card-header bg-dark text-white">AJAX Endpoint Test</div>
    <div class="card-body">';

echo '<div class="row">
    <div class="col-md-6">
        <h6>Test Enrollment:</h6>
        <button onclick="testEnroll()" class="btn btn-primary mb-2">Test /course/enroll</button>
        <div id="enroll-result" class="mt-2"></div>
    </div>
    <div class="col-md-6">
        <h6>Test Unenrollment:</h6>
        <button onclick="testUnenroll()" class="btn btn-danger mb-2">Test /course/unenroll</button>
        <div id="unenroll-result" class="mt-2"></div>
    </div>
</div>';

echo '</div></div>';

// 6. File System Check
echo '<div class="card mb-3">
    <div class="card-header bg-secondary text-white">File System Check</div>
    <div class="card-body">';

$files_to_check = [
    'app/Controllers/Course.php' => 'Course Controller',
    'app/Controllers/Student.php' => 'Student Controller',
    'app/Controllers/Auth.php' => 'Auth Controller',
    'app/Models/EnrollmentModel.php' => 'Enrollment Model',
    'app/Models/CourseModel.php' => 'Course Model',
    'app/Views/student/enrollment_dashboard.php' => 'Enrollment Dashboard View',
    'app/Views/auth/quick_login.php' => 'Quick Login View',
    'app/Config/Routes.php' => 'Routes Configuration'
];

foreach ($files_to_check as $file => $description) {
    if (file_exists($file)) {
        echo '<div class="alert alert-success"><i class="fas fa-check me-2"></i>' . $description . ' - EXISTS</div>';
    } else {
        echo '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>' . $description . ' - MISSING</div>';
    }
}

echo '</div></div>';

// 7. Quick Actions
echo '<div class="card mb-3">
    <div class="card-header bg-primary text-white">Quick Actions</div>
    <div class="card-body">';

echo '<div class="row">
    <div class="col-md-3">
        <a href="http://localhost:8080/quick-login" class="btn btn-primary w-100 mb-2">
            <i class="fas fa-sign-in-alt me-2"></i>Quick Login
        </a>
    </div>
    <div class="col-md-3">
        <a href="http://localhost:8080/enrollment-dashboard" class="btn btn-success w-100 mb-2">
            <i class="fas fa-tachometer-alt me-2"></i>Enrollment Dashboard
        </a>
    </div>
    <div class="col-md-3">
        <a href="test_enrollment.php" class="btn btn-info w-100 mb-2">
            <i class="fas fa-play me-2"></i>Test Enrollment
        </a>
    </div>
    <div class="col-md-3">
        <a href="debug_enrollment.php" class="btn btn-warning w-100 mb-2">
            <i class="fas fa-bug me-2"></i>Debug Tool
        </a>
    </div>
</div>';

echo '</div></div>';

echo '</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function testEnroll() {
    $("#enroll-result").html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> Testing...");
    
    $.post("http://localhost:8080/course/enroll", {course_id: 1}, function(response) {
        $("#enroll-result").html("<div class=\"alert alert-success\">SUCCESS: " + JSON.stringify(response, null, 2) + "</div>");
    }, "json").fail(function(xhr) {
        $("#enroll-result").html("<div class=\"alert alert-danger\">ERROR " + xhr.status + ": " + xhr.responseText + "</div>");
    });
}

function testUnenroll() {
    $("#unenroll-result").html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> Testing...");
    
    $.post("http://localhost:8080/course/unenroll", {course_id: 1}, function(response) {
        $("#unenroll-result").html("<div class=\"alert alert-success\">SUCCESS: " + JSON.stringify(response, null, 2) + "</div>");
    }, "json").fail(function(xhr) {
        $("#unenroll-result").html("<div class=\"alert alert-danger\">ERROR " + xhr.status + ": " + xhr.responseText + "</div>");
    });
}
</script>

</body>
</html>';
?>
