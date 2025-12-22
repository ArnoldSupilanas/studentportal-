<?php
session_start();

// Simple login check
if (!($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false)) {
    header("Location: quick_login.php");
    exit;
}

// Check if user has admin role
$role = $_SESSION['role'] ?? 'student';
if ($role !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Redirect to enrollment dashboard
header("Location: /ITE311-SUPILANAS/admin/enrollments");
exit;
?>
