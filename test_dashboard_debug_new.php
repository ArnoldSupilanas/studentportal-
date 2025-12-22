<?php
// Include CodeIgniter helper
if (!function_exists('helper')) {
    function helper($name) {
        // Mock helper function
        return true;
    }
}

// Mock base_url function
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        return 'http://localhost:8080/' . ltrim($uri, '/');
    }
}

// Mock esc function
if (!function_exists('esc')) {
    function esc($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

// Mock character_limiter function
if (!function_exists('character_limiter')) {
    function character_limiter($string, $limit = 100) {
        return strlen($string) > $limit ? substr($string, 0, $limit) . '...' : $string;
    }
}

// Simple test to check if dashboard loads without JavaScript errors
session_start();

// Mock session data for testing
$_SESSION['is_logged_in'] = true;
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 2;
$_SESSION['role'] = 'student';
$_SESSION['name'] = 'Test Student';
$_SESSION['email'] = 'student@test.com';

// Mock data for dashboard
$data = [
    'is_logged_in' => true,
    'user_id' => 2,
    'name' => 'Test Student',
    'email' => 'student@test.com',
    'role' => 'student',
    'role_data' => [
        'total_courses' => 3,
        'completed_assignments' => 5,
        'pending_assignments' => 2,
        'upcoming_deadlines' => 1
    ],
    'enrolled_courses' => [
        [
            'course_id' => 1,
            'title' => 'Test Course 1',
            'description' => 'This is a test course for debugging',
            'enrollment_date' => '2025-12-01'
        ]
    ],
    'available_courses' => [
        [
            'id' => 2,
            'title' => 'Available Course 1',
            'description' => 'This is an available course for testing',
            'instructor_name' => 'Test Instructor'
        ]
    ]
];

// Mock view function
if (!function_exists('view')) {
    function view($template, $data = []) {
        if ($template === 'templates/header') {
            return '<!DOCTYPE html><html><head><title>Dashboard Test</title></head><body>';
        } elseif ($template === 'templates/footer') {
            return '</body></html>';
        }
        return '';
    }
}

// Load dashboard view
view('templates/header');
include 'app/Views/auth/dashboard.php';
view('templates/footer');
?>
