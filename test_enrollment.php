<?php
// Simple test page to verify enrollment system
session_start();

// Check if user is logged in
$isLoggedIn = $_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false;
$userId = $_SESSION['userID'] ?? $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['role'] ?? 'student';

// If not logged in, show login form
if (!$isLoggedIn || !$userId) {
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Enrollment System Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Login to Test Enrollment System</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="debug_login.php">
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" value="student@lms.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" value="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <hr>
                        <h6>Test Users:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Student:</strong> student@lms.com / password</li>
                            <li><strong>Admin:</strong> admin@lms.com / password</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
    exit;
}

// User is logged in, show enrollment test
echo '<!DOCTYPE html>
<html>
<head>
    <title>Enrollment System Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .list-group-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container">
            <a class="navbar-brand" href="#">Enrollment System Test</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white">
                    <i class="fas fa-user me-2"></i>' . ucfirst($userRole) . ' User
                </span>
                <a href="debug_login.php?logout=1" class="btn btn-outline-light btn-sm ms-2">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3><i class="fas fa-graduation-cap me-2"></i>Course Enrollment System Test</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">This page tests the course enrollment functionality.</p>';

// Database connection test
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lms_supilanas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo '<div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i>Database connection successful!
    </div>';
    
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
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<div class="row">
        <div class="col-md-6">
            <h4><i class="fas fa-book me-2"></i>Available Courses</h4>
            <div class="list-group">';
    
    $availableCount = 0;
    foreach ($courses as $course) {
        if ($course['status'] === 'available') {
            $availableCount++;
            echo '<div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <h6 class="mb-1">' . htmlspecialchars($course['title']) . '</h6>
                        <p class="mb-1 text-muted">' . htmlspecialchars(substr($course['description'], 0, 100)) . '...</p>
                        <small class="text-muted">Instructor ID: ' . $course['instructor_id'] . '</small>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-sm btn-gradient enroll-btn" 
                                data_course_id="' . $course['id'] . '"
                                data_course_title="' . htmlspecialchars($course['title']) . '">
                            <i class="fas fa-plus-circle me-1"></i>Enroll Now
                        </button>
                    </div>
                </div>
            </div>';
        }
    }
    
    if ($availableCount === 0) {
        echo '<p class="text-muted">No available courses to enroll in.</p>';
    }
    
    echo '</div></div>';
    
    // Show enrolled courses
    echo '<div class="col-md-6">
        <h4><i class="fas fa-user-graduate me-2"></i>My Enrollments</h4>
        <div class="list-group">';
    
    $stmt = $pdo->prepare("
        SELECT e.*, c.title, c.description
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE e.user_id = ? AND e.status = 'enrolled'
        ORDER BY e.enrollment_date DESC
    ");
    $stmt->execute([$userId]);
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($enrollments as $enrollment) {
        echo '<div class="list-group-item">
            <div class="d-flex w-100 justify-content-between">
                <div>
                    <h6 class="mb-1">' . htmlspecialchars($enrollment['title']) . '</h6>
                    <p class="mb-1 text-muted">' . htmlspecialchars(substr($enrollment['description'], 0, 100)) . '...</p>
                    <small class="text-muted">
                        Progress: ' . $enrollment['progress'] . '% | 
                        Status: <span class="badge bg-success">' . $enrollment['status'] . '</span>
                    </small>
                </div>
                <div class="text-end">
                    <small class="text-muted">Enrolled: ' . date('M j, Y', strtotime($enrollment['enrollment_date'])) . '</small>
                    <br>
                    <button class="btn btn-sm btn-outline-danger unenroll-btn" data_course_id="' . $enrollment['course_id'] . '">
                        <i class="fas fa-times me-1"></i>Drop
                    </button>
                </div>
            </div>
        </div>';
    }
    
    if (empty($enrollments)) {
        echo '<p class="text-muted">You are not enrolled in any courses yet.</p>';
    }
    
    echo '</div></div>';
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>Database error: ' . htmlspecialchars($e->getMessage()) . '
    </div>';
}

echo '</div></div></div>

<script>
$(document).ready(function() {
    $(".enroll-btn").on("click", function(e) {
        e.preventDefault();
        var $btn = $(this);
        var courseId = $btn.data("course_id");
        var courseTitle = $btn.data("course_title");
        
        $btn.html("<i class=\"fas fa-spinner fa-spin me-1\"></i>Enrolling...").prop("disabled", true);
        
        $.post("course/enroll", {course_id: courseId}, function(response) {
            if (response.success) {
                alert("Successfully enrolled in " + courseTitle + "!");
                location.reload();
            } else {
                alert("Error: " + response.message);
                $btn.html("<i class=\"fas fa-plus-circle me-1\"></i>Enroll Now").prop("disabled", false);
            }
        }, "json").fail(function() {
            alert("Network error. Please try again.");
            $btn.html("<i class=\"fas fa-plus-circle me-1\"></i>Enroll Now").prop("disabled", false);
        });
    });
    
    $(".unenroll-btn").on("click", function(e) {
        e.preventDefault();
        if (confirm("Are you sure you want to drop this course?")) {
            var $btn = $(this);
            var courseId = $btn.data("course_id");
            
            $btn.html("<i class=\"fas fa-spinner fa-spin me-1\"></i>Dropping...").prop("disabled", true);
            
            $.post("course/unenroll", {course_id: courseId}, function(response) {
                if (response.success) {
                    alert("Successfully dropped the course!");
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                    $btn.html("<i class=\"fas fa-times me-1\"></i>Drop").prop("disabled", false);
                }
            }, "json").fail(function() {
                alert("Network error. Please try again.");
                $btn.html("<i class=\"fas fa-times me-1\"></i>Drop").prop("disabled", false);
            });
        }
    });
});
</script>

</body>
</html>';
?>
