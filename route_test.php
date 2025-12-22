<?php
// Simple route test to verify enrollment endpoints
echo '<!DOCTYPE html>
<html>
<head>
    <title>Route Test - Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Enrollment System Route Test</h2>
        
        <div class="card">
            <div class="card-header">
                <h4>Test Enrollment Endpoints</h4>
            </div>
            <div class="card-body">
                <p>Click the buttons below to test each endpoint:</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Available Routes:</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>/course/enroll</strong> - POST endpoint for enrollment
                                <br><small class="text-muted">Used by AJAX to enroll students</small>
                            </li>
                            <li class="list-group-item">
                                <strong>/course/unenroll</strong> - POST endpoint for unenrollment
                                <br><small class="text-muted">Used by AJAX to drop courses</small>
                            </li>
                            <li class="list-group-item">
                                <strong>/student/dashboard</strong> - GET endpoint for student dashboard
                                <br><small class="text-muted">Main enrollment interface</small>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Quick Access:</h5>
                        <div class="d-grid gap-2">
                            <a href="test_enrollment.php" class="btn btn-primary">
                                <i class="fas fa-play me-2"></i>Test Enrollment System
                            </a>
                            <a href="/student/dashboard" class="btn btn-info">
                                <i class="fas fa-tachometer-alt me-2"></i>Student Dashboard
                            </a>
                            <a href="/course" class="btn btn-success">
                                <i class="fas fa-book me-2"></i>Course Catalog
                            </a>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h5>Database Status:</h5>';
                
// Test database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lms_supilanas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo '<div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i>Database connection: <strong>OK</strong>
    </div>';
    
    // Check tables
    $tables = ['users', 'courses', 'enrollments'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo '<div class="alert alert-info">
            <i class="fas fa-table me-2"></i>' . ucfirst($table) . ' table: <strong>' . $count . ' records</strong>
        </div>';
    }
    
    // Show sample data
    echo '<h6>Sample Available Courses:</h6>';
    $stmt = $pdo->query("SELECT id, title FROM courses LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<button class="btn btn-sm btn-outline-primary me-2 mb-2" onclick="testEnroll(' . $row['id'] . ')">
            Enroll in: ' . htmlspecialchars($row['title']) . '
        </button>';
    }
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>Database error: ' . htmlspecialchars($e->getMessage()) . '
    </div>';
}

echo '</div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function testEnroll(courseId) {
        $.post("/course/enroll", {course_id: courseId}, function(response) {
            alert("Response: " + JSON.stringify(response, null, 2));
        }, "json").fail(function(xhr) {
            alert("Error: " + xhr.status + " - " + xhr.responseText);
        });
    }
    </script>
</body>
</html>';
?>
