<?php

namespace App\Controllers;

class Debug extends BaseController
{
    public function index()
    {
        echo "<h1>Debug Controller</h1>";
        echo "<h2>Current Session:</h2>";
        echo "<pre>";
        print_r(session()->get());
        echo "</pre>";
        
        echo "<h2>Auth Check:</h2>";
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        echo "Is Logged In: " . ($isLoggedIn ? 'YES' : 'NO') . "<br>";
        
        echo "<h2>Role Check:</h2>";
        $role = session()->get('role');
        echo "Role: " . ($role ?? 'NULL') . "<br>";
        
        echo "<h2>Available Routes:</h2>";
        echo "<a href='" . base_url('login') . "'>Login</a><br>";
        echo "<a href='" . base_url('teacher/dashboard') . "'>Teacher Dashboard</a><br>";
        echo "<a href='" . base_url('admin/dashboard') . "'>Admin Dashboard</a><br>";
        echo "<a href='" . base_url('announcements') . "'>Announcements</a><br>";
    }
    
    public function testLogin()
    {
        // Simulate successful login
        session()->regenerate(true);
        
        $sessionData = [
            'userID' => 2,
            'user_id' => 2,
            'name' => 'Test Instructor',
            'first_name' => 'Test',
            'last_name' => 'Instructor',
            'email' => 'instructor@lms.com',
            'role' => 'teacher',
            'is_logged_in' => true,
            'logged_in' => true,
            'login_time' => time()
        ];
        
        session()->set($sessionData);
        
        echo "<h1>Simulated Login Successful</h1>";
        echo "<p>Session data has been set.</p>";
        echo "<p><a href='" . base_url('debug') . "'>View Session</a></p>";
        echo "<p><a href='" . base_url('teacher/dashboard') . "'>Go to Teacher Dashboard</a></p>";
        
        echo "<h2>Session Data:</h2>";
        echo "<pre>";
        print_r(session()->get());
        echo "</pre>";
    }
    
    public function clearSession()
    {
        session()->destroy();
        echo "<h1>Session Cleared</h1>";
        echo "<p><a href='" . base_url('debug') . "'>Back to Debug</a></p>";
    }
}
