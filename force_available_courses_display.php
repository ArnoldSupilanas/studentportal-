<?php
// Force available courses to display by ensuring data is properly set
echo "Forcing available courses to display in dashboard...\n";

try {
    // Connect to database directly
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Get active courses
    $stmt = $pdo->query("SELECT id, title, description, instructor_name FROM courses WHERE status = 'active'");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($courses) . " active courses in database\n";
    
    // Create a simple dashboard test to verify courses display
    $dashboardHtml = '<!DOCTYPE html>
<html>
<head>
    <title>Available Courses Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Available Courses Test</h2>
        <div class="alert alert-info">
            <strong>Debug Info:</strong> This page shows if available courses are displaying properly.
        </div>';
    
    if (count($courses) > 0) {
        $dashboardHtml .= '<div class="row">';
        foreach ($courses as $course) {
            $dashboardHtml .= '<div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">' . htmlspecialchars($course['title']) . '</h6>
                        <p class="card-text"><small>By: ' . htmlspecialchars($course['instructor_name']) . '</small></p>
                        <button class="btn btn-primary btn-sm enroll-btn" 
                                data-course-id="' . $course['id'] . '" 
                                data-course-title="' . htmlspecialchars($course['title']) . '"
                                onclick="enrollInCourse(' . $course['id'] . ')">
                            <i class="bi bi-plus-circle"></i> Enroll
                        </button>
                    </div>
                </div>
            </div>';
        }
        $dashboardHtml .= '</div>';
    } else {
        $dashboardHtml .= '<div class="alert alert-warning">
            <strong>No courses available</strong> No active courses found in the database.
        </div>';
    }
    
    $dashboardHtml .= '
    </div>
    <script>
        function enrollInCourse(courseId) {
            console.log("ENROLLMENT STARTED - Course ID:", courseId);
            alert("Enrollment clicked for course ID: " + courseId);
        }
    </script>
</body>
</html>';
    
    // Save test file
    file_put_contents('test_available_courses.html', $dashboardHtml);
    
    echo "✅ Test file created: test_available_courses.html\n";
    echo "✅ This shows how available courses should display\n";
    echo "✅ Open test_available_courses.html to verify course display\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
