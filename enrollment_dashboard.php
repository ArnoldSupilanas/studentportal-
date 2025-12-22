<?php
session_start();
// Simple login check
if (!($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false)) {
    header("Location: quick_login.php");
    exit;
}

$userId = $_SESSION['userID'] ?? $_SESSION['user_id'] ?? 3;
$userName = ($_SESSION['first_name'] ?? 'Student') . ' ' . ($_SESSION['last_name'] ?? '');

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lms_supilanas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
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
    
    // Get enrolled courses
    $stmt = $pdo->prepare("
        SELECT e.*, c.title, c.description
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE e.user_id = ? AND e.status = 'enrolled'
        ORDER BY e.enrollment_date DESC
    ");
    $stmt->execute([$userId]);
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .enroll-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .enroll-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .enroll-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .course-card {
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        .course-card:hover {
            border-left-color: #764ba2;
            background: #f8f9fa;
        }
        .stats-card {
            text-align: center;
            padding: 20px;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .alert-custom {
            border-radius: 10px;
            border: none;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
        }
        .new-enrollment {
            animation: pulse 2s;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap text-primary me-2"></i>
                <strong>Student Portal</strong>
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text">
                    <i class="fas fa-user-circle text-primary me-2"></i>
                    <strong><?= htmlspecialchars($userName) ?></strong>
                </span>
                <a href="quick_login.php?logout=1" class="btn btn-outline-danger btn-sm ms-3">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="text-center text-white mb-4">
            <h1 class="display-4"><i class="fas fa-graduation-cap me-3"></i>Student Dashboard</h1>
            <p class="lead">Manage your course enrollments and track your academic progress</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="dashboard-card stats-card">
                    <i class="fas fa-book text-primary fa-2x mb-2"></i>
                    <div class="stats-number"><?= count($enrollments) ?></div>
                    <div class="text-muted">Enrolled Courses</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card stats-card">
                    <i class="fas fa-plus-circle text-success fa-2x mb-2"></i>
                    <div class="stats-number"><?= count(array_filter($courses, fn($c) => $c['status'] === 'available')) ?></div>
                    <div class="text-muted">Available Courses</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card stats-card">
                    <i class="fas fa-trophy text-warning fa-2x mb-2"></i>
                    <div class="stats-number">A</div>
                    <div class="text-muted">Current GPA</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card stats-card">
                    <i class="fas fa-chart-line text-info fa-2x mb-2"></i>
                    <div class="stats-number">85%</div>
                    <div class="text-muted">Avg Progress</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Available Courses -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Available Courses</h5>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php else: ?>
                            <?php 
                            $availableCourses = array_filter($courses, fn($c) => $c['status'] === 'available');
                            if (!empty($availableCourses)): 
                            ?>
                                <?php foreach ($availableCourses as $course): ?>
                                    <div class="course-card card mb-3" id="course-<?= $course['id'] ?>">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-2">
                                                        <i class="fas fa-book-open text-primary me-2"></i>
                                                        <?= htmlspecialchars($course['title']) ?>
                                                    </h6>
                                                    <p class="text-muted small mb-2">
                                                        <?= htmlspecialchars(substr($course['description'], 0, 120)) ?>...
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user-tie me-1"></i>
                                                        Instructor ID: <?= $course['instructor_id'] ?>
                                                    </small>
                                                </div>
                                                <button class="enroll-btn" 
                                                        onclick="enrollCourse(<?= $course['id'] ?>, '<?= htmlspecialchars($course['title']) ?>')"
                                                        id="btn-<?= $course['id'] ?>">
                                                    <i class="fas fa-plus me-1"></i>Enroll
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No available courses at the moment.</p>
                                    <small>Check back later for new course offerings!</small>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- My Enrollments -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>My Enrollments</h5>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <?php if (!empty($enrollments)): ?>
                            <?php foreach ($enrollments as $enrollment): ?>
                                <div class="course-card card mb-3" id="enrollment-<?= $enrollment['course_id'] ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <?= htmlspecialchars($enrollment['title']) ?>
                                                </h6>
                                                <p class="text-muted small mb-2">
                                                    <?= htmlspecialchars(substr($enrollment['description'], 0, 120)) ?>...
                                                </p>
                                                <div class="d-flex align-items-center gap-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Enrolled: <?= date('M j, Y', strtotime($enrollment['enrollment_date'])) ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-chart-line me-1"></i>
                                                        Progress: <?= $enrollment['progress'] ?>%
                                                    </small>
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="unenrollCourse(<?= $enrollment['course_id'] ?>, '<?= htmlspecialchars($enrollment['title']) ?>')">
                                                <i class="fas fa-times me-1"></i>Drop
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-book-reader fa-3x mb-3"></i>
                                <p>You haven't enrolled in any courses yet.</p>
                                <small>Browse available courses and click "Enroll" to get started!</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function enrollCourse(courseId, courseTitle) {
        if (!confirm('Are you sure you want to enroll in "' + courseTitle + '"?')) {
            return;
        }
        
        const $btn = $('#btn-' + courseId);
        const originalText = $btn.html();
        
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Enrolling...').prop('disabled', true);
        
        $.post('/course/enroll', {course_id: courseId}, function(response) {
            if (response.success) {
                showAlert('Successfully enrolled in ' + courseTitle + '!', 'success');
                
                // Move course to enrolled section with animation
                const $course = $('#course-' + courseId);
                $course.fadeOut(500, function() {
                    $(this).remove();
                    location.reload(); // Simple reload for now
                });
            } else {
                showAlert(response.message || 'Enrollment failed', 'danger');
                $btn.html(originalText).prop('disabled', false);
            }
        }, 'json').fail(function(xhr) {
            showAlert('Network error. Please try again.', 'danger');
            $btn.html(originalText).prop('disabled', false);
        });
    }
    
    function unenrollCourse(courseId, courseTitle) {
        if (!confirm('Are you sure you want to drop "' + courseTitle + '"?')) {
            return;
        }
        
        const $enrollment = $('#enrollment-' + courseId);
        
        $enrollment.css('opacity', '0.5');
        
        $.post('/course/unenroll', {course_id: courseId}, function(response) {
            if (response.success) {
                showAlert('Successfully dropped ' + courseTitle, 'success');
                $enrollment.fadeOut(500, function() {
                    $(this).remove();
                    location.reload(); // Simple reload for now
                });
            } else {
                showAlert(response.message || 'Failed to drop course', 'danger');
                $enrollment.css('opacity', '1');
            }
        }, 'json').fail(function(xhr) {
            showAlert('Network error. Please try again.', 'danger');
            $enrollment.css('opacity', '1');
        });
    }
    
    function showAlert(message, type) {
        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show alert-custom position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        ${type === 'success' ? '<i class="fas fa-check-circle"></i>' : 
                          type === 'danger' ? '<i class="fas fa-exclamation-triangle"></i>' : 
                          '<i class="fas fa-info-circle"></i>'}
                    </div>
                    <div class="flex-grow-1">
                        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong><br>
                        <small>${message}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        `;
        
        $('body').prepend(alertHtml);
        
        setTimeout(function() {
            $('#' + alertId).alert('close');
        }, 5000);
    }
    </script>
</body>
</html>
