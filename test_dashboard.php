<?php
// Simple test to verify dashboard enrollment system
session_start();

// Quick login if not logged in
if (!($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false)) {
    $_SESSION['is_logged_in'] = true;
    $_SESSION['userID'] = 3;
    $_SESSION['user_id'] = 3;
    $_SESSION['role'] = 'student';
    $_SESSION['first_name'] = 'Bob';
    $_SESSION['last_name'] = 'Student';
}

// Load dashboard data
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lms_supilanas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $userId = $_SESSION['userID'] ?? $_SESSION['user_id'] ?? 3;
    
    // Get enrolled courses
    $stmt = $pdo->prepare("
        SELECT e.*, c.title, c.description
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE e.user_id = ? AND e.status = 'enrolled'
        ORDER BY e.enrollment_date DESC
    ");
    $stmt->execute([$userId]);
    $enrolledCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get available courses
    $stmt = $pdo->prepare("
        SELECT c.id, c.title, c.description, c.instructor_id,
               CASE WHEN e.user_id IS NOT NULL THEN 'enrolled' ELSE 'available' END as status
        FROM courses c
        LEFT JOIN enrollments e ON c.id = e.course_id AND e.user_id = ?
        WHERE c.status = 'active'
        ORDER BY c.title
    ");
    $stmt->execute([$userId]);
    $allCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $availableCourses = array_filter($allCourses, fn($c) => $c['status'] === 'available');
    
} catch (PDOException $e) {
    $error = $e->getMessage();
}

echo '<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Enrollment Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .enroll-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .enroll-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="text-center text-white mb-4">
            <h1><i class="fas fa-graduation-cap me-3"></i>Student Dashboard Test</h1>
            <p>Enrollment System Status Check</p>
        </div>';

if (isset($error)) {
    echo '<div class="alert alert-danger">Database Error: ' . htmlspecialchars($error) . '</div>';
} else {
    echo '<div class="row">
        <div class="col-md-6">
            <div class="dashboard-card">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-user-graduate me-2"></i>My Enrollments (' . count($enrolledCourses) . ')</h5>
                </div>
                <div class="card-body">';
    
    if (!empty($enrolledCourses)) {
        foreach ($enrolledCourses as $enrollment) {
            echo '<div class="card mb-2">
                <div class="card-body">
                    <h6>' . htmlspecialchars($enrollment['title']) . '</h6>
                    <p class="small text-muted">' . htmlspecialchars(substr($enrollment['description'], 0, 100)) . '...</p>
                    <small class="text-muted">Progress: ' . $enrollment['progress'] . '%</small>
                </div>
            </div>';
        }
    } else {
        echo '<p class="text-muted">No enrollments yet.</p>';
    }
    
    echo '</div></div></div>';
    
    echo '<div class="col-md-6">
        <div class="dashboard-card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-plus-circle me-2"></i>Available Courses (' . count($availableCourses) . ')</h5>
            </div>
            <div class="card-body">';
    
    if (!empty($availableCourses)) {
        foreach ($availableCourses as $course) {
            echo '<div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>' . htmlspecialchars($course['title']) . '</h6>
                            <p class="small text-muted">' . htmlspecialchars(substr($course['description'], 0, 100)) . '...</p>
                        </div>
                        <button class="enroll-btn" onclick="enroll(' . $course['id'] . ', \'' . htmlspecialchars($course['title']) . '\')">
                            <i class="fas fa-plus me-1"></i>Enroll
                        </button>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo '<p class="text-muted">No available courses.</p>';
    }
    
    echo '</div></div></div>';
}

echo '</div>

<script>
function enroll(courseId, courseTitle) {
    if (confirm("Enroll in " + courseTitle + "?")) {
        $.post("/course/enroll", {course_id: courseId}, function(response) {
            alert("Enrollment: " + JSON.stringify(response, null, 2));
            location.reload();
        }, "json").fail(function(xhr) {
            alert("Error: " + xhr.responseText);
        });
    }
}
</script>

</body>
</html>';
?>
