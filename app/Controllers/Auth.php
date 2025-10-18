<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                // Set session data
                $sessionData = [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'is_logged_in' => true
                ];
                
                session()->set($sessionData);

                // Redirect based on role
                switch ($user['role']) {
                    case 'student':
                        return redirect()->to('/announcements');
                        break;
                    case 'instructor':
                        return redirect()->to('/teacher/dashboard');
                        break;
                    case 'admin':
                        return redirect()->to('/admin/dashboard');
                        break;
                    default:
                        return redirect()->to('/announcements');
                }
            } else {
                session()->setFlashdata('error', 'Invalid email or password');
                return redirect()->to('/auth/login');
            }
        }

        // Show login form
        $data = [
            'title' => 'Login',
            'page_title' => 'Login to LMS'
        ];
        
        return view('login', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}