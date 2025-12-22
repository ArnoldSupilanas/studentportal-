<?php
// Simple debug script to test login flow
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'lms_supilanas';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    echo "<h1>Login Debug Test</h1>";
    
    // Test 1: Check if users exist
    echo "<h2>Test 1: Database Users</h2>";
    $result = $conn->query("SELECT id, email, role, status FROM users LIMIT 5");
    echo "<table border='1'><tr><th>ID</th><th>Email</th><th>Role</th><th>Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td>{$row['role']}</td><td>{$row['status']}</td></tr>";
    }
    echo "</table>";
    
    // Test 2: Simulate login
    echo "<h2>Test 2: Simulated Login</h2>";
    $testEmail = 'instructor@lms.com';
    $testPassword = 'instructor123';
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $testEmail);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if ($user) {
        echo "<p>User found: {$user['email']}</p>";
        echo "<p>Role: {$user['role']}</p>";
        echo "<p>Status: {$user['status']}</p>";
        
        // Test password
        if (password_verify($testPassword, $user['password'])) {
            echo "<p style='color:green'>Password verification: SUCCESS</p>";
            
            // Simulate session data
            $_SESSION['userID'] = $user['id'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_logged_in'] = true;
            $_SESSION['logged_in'] = true;
            
            echo "<p>Session data set</p>";
            
            // Test redirect URL
            $role = $user['role'];
            if ($role === 'instructor') {
                $role = 'teacher';
            }
            
            $redirectUrl = '/announcements'; // default
            switch ($role) {
                case 'student':
                    $redirectUrl = '/announcements';
                    break;
                case 'teacher':
                    $redirectUrl = '/teacher/dashboard';
                    break;
                case 'admin':
                    $redirectUrl = '/admin/dashboard';
                    break;
                default:
                    $redirectUrl = '/announcements';
            }
            
            echo "<p>Would redirect to: <strong>{$redirectUrl}</strong></p>";
            
        } else {
            echo "<p style='color:red'>Password verification: FAILED</p>";
        }
    } else {
        echo "<p style='color:red'>User not found: {$testEmail}</p>";
    }
    
    echo "<h2>Test 3: Current Session Data</h2>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
