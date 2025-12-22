<?php
// Debug dashboard session and course loading
echo "Debugging dashboard session and course loading...\n";

try {
    // Start session
    session_start();
    
    // Simulate user session
    $_SESSION['is_logged_in'] = true;
    $_SESSION['logged_in'] = true;
    $_SESSION['userID'] = 3;
    $_SESSION['user_id'] = 3;
    $_SESSION['role'] = 'student';
    $_SESSION['first_name'] = 'Bob';
    $_SESSION['last_name'] = 'Student';
    $_SESSION['email'] = 'student@lms.com';
    
    echo "Session set for student user\n";
    
    // Load Auth controller
    require_once 'app/Controllers/Auth.php';
    $auth = new App\Controllers\Auth();
    
    // Call dashboard method
    $dashboardData = $auth->dashboard();
    
    echo "Dashboard method called\n";
    
    // Check if available_courses data is in the response
    if (isset($dashboardData['data']['available_courses'])) {
        $courses = $dashboardData['data']['available_courses'];
        echo "✅ Available courses found: " . count($courses) . "\n";
        foreach ($courses as $course) {
            echo "- {$course['title']} (ID: {$course['id']})\n";
        }
    } else {
        echo "❌ No available_courses data found in dashboard response\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
