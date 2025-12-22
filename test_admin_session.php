<?php
// Test admin session and authentication
session_start();

echo "<h2>Admin Session Test</h2>";

// Set up admin session for testing
$_SESSION['is_logged_in'] = true;
$_SESSION['role'] = 'admin';
$_SESSION['user_id'] = 1;
$_SESSION['first_name'] = 'Admin';
$_SESSION['last_name'] = 'User';

echo "<h3>Session Set:</h3>";
echo "<pre>";
echo "is_logged_in: " . $_SESSION['is_logged_in'] . "\n";
echo "role: " . $_SESSION['role'] . "\n";
echo "user_id: " . $_SESSION['user_id'] . "\n";
echo "</pre>";

echo "<h3>Current Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<p><a href='/admin/courses'>Go to Admin Courses</a></p>";
echo "<p><a href='/debug_course_creation.php'>Test Course Creation</a></p>";
?>
