<?php

namespace App\Controllers;

class TestSession extends BaseController
{
    public function index()
    {
        // Set a test session value
        session()->set('test', 'Session is working!');
        
        // Get all session data
        $sessionData = session()->get();
        
        echo "<h1>Session Test</h1>";
        echo "<h2>Session Data:</h2>";
        echo "<pre>";
        print_r($sessionData);
        echo "</pre>";
        
        echo "<h2>Session ID:</h2>";
        echo session_id();
        
        echo "<h2>Is Logged In:</h2>";
        echo session()->get('is_logged_in') ? 'Yes' : 'No';
    }
}
