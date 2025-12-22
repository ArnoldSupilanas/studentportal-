<?php

namespace App\Controllers;

class TestLogin extends BaseController
{
    public function index()
    {
        // Simulate successful login and redirect to dashboard
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
        
        // Redirect to dashboard
        return redirect()->to('/dashboard');
    }
}
