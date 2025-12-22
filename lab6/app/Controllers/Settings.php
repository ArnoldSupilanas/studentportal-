<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Settings extends BaseController
{
    public function index()
    {
        // Authorization check - ensure user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access settings');
            return redirect()->to('/login');
        }
        
        // Get user data from session
        $userData = [
            'first_name' => session()->get('first_name') ?? 'John',
            'last_name' => session()->get('last_name') ?? 'Doe',
            'email' => session()->get('email') ?? 'john.doe@example.com',
            'role' => session()->get('role') ?? 'user',
            'user_id' => $userId
        ];
        
        $data = [
            'title' => 'Settings',
            'page_title' => 'Account Settings',
            'description' => 'Manage your account settings and preferences.',
            'user' => $userData
        ];
        
        return view('settings', $data);
    }
}
