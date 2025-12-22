<?php
// Test direct access to teacher dashboard route
session_start();

// Simulate logged-in teacher session
$_SESSION['userID'] = 2;
$_SESSION['user_id'] = 2;
$_SESSION['email'] = 'instructor@lms.com';
$_SESSION['role'] = 'teacher';
$_SESSION['is_logged_in'] = true;
$_SESSION['logged_in'] = true;

echo "<h1>Teacher Route Test</h1>";
echo "<p>Session data set for teacher user</p>";
echo "<p><a href='/ITE311-SUPILANAS/teacher/dashboard'>Test Teacher Dashboard Route</a></p>";
echo "<p><a href='/ITE311-SUPILANAS/test-session'>Test Session</a></p>";
echo "<p><a href='/ITE311-SUPILANAS/login'>Go to Login</a></p>";

echo "<h2>Current Session:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
