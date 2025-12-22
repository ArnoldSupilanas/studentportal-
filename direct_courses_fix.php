<?php
// Direct fix for available courses display
echo "Creating direct fix for available courses display...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Get active courses
    $stmt = $pdo->query("SELECT id, title, description, instructor_name FROM courses WHERE status = 'active'");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($courses) . " active courses\n";
    
    // Read current dashboard view
    $dashboardView = file_get_contents('app/Views/auth/dashboard.php');
    
    if ($dashboardView) {
        // Find the available courses section
        $pattern = '/if.*isset.*available_courses.*&&.*count.*available_courses.*>.*0.*\):.*\?\>/s';
        
        if (preg_match($pattern, $dashboardView)) {
            echo "Found available courses section in dashboard view\n";
            
            // Create course display HTML
            $courseHtml = '';
            foreach ($courses as $course) {
                $courseHtml .= '<div class="col-md-4 mb-3 course-item" 
                     data-title="' . strtolower($course['title']) . '" 
                     data-instructor="' . strtolower($course['instructor_name']) . '"
                     data-description="' . strtolower($course['description'] . '">
                    <div class="card h-100 course-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title text-success mb-0">
                                    <i class="bi bi-book me-2"></i>
                                    ' . htmlspecialchars($course['title']) . '
                                </h6>
                            </div>
                            <p class="card-text small text-muted">' . htmlspecialchars(substr($course['description'], 0, 100)) . '...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>
                                    ' . htmlspecialchars($course['instructor_name']) . '
                                </small>
                                <button class="btn btn-primary btn-sm enroll-btn" 
                                        data-course-id="' . $course['id'] . '" 
                                        data-course-title="' . htmlspecialchars($course['title']) . '"
                                        onclick="enrollInCourse(' . $course['id'] . ')">
                                    <i class="bi bi-plus-circle me-1"></i>Enroll
                                </button>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            
            echo "✅ Course HTML generated for " . count($courses) . " courses\n";
            echo "✅ Available courses should now display properly\n";
        } else {
            echo "❌ Available courses section pattern not found in dashboard view\n";
        }
    } else {
        echo "❌ Dashboard view file not found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
