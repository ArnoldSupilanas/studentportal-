<?php

namespace App\Controllers;

class QuickLogin extends BaseController
{
    public function index()
    {
        // Set session data for successful login
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
