<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'page_title' => 'Welcome, Admin!',
            'description' => 'Manage the entire system from this administrative dashboard.'
        ];
        
        return view('admin_dashboard', $data);
    }
    
    public function users()
    {
        $userModel = new \App\Models\UserModel();
        $users = $userModel->findAll();
        
        $data = [
            'title' => 'Manage Users',
            'page_title' => 'User Management',
            'description' => 'View, edit, and manage all system users.',
            'users' => $users
        ];
        
        return view('admin/users', $data);
    }
    
    public function courses()
    {
        $data = [
            'title' => 'Manage Courses',
            'page_title' => 'Course Management',
            'description' => 'Create, edit, and manage all courses in the system.',
            'courses' => [
                ['id' => 1, 'name' => 'Mathematics 101', 'code' => 'MATH101', 'teacher' => 'Dr. Smith', 'students' => 25, 'status' => 'active'],
                ['id' => 2, 'name' => 'Computer Science', 'code' => 'CS101', 'teacher' => 'Prof. Johnson', 'students' => 20, 'status' => 'active'],
                ['id' => 3, 'name' => 'Physics 201', 'code' => 'PHY201', 'teacher' => 'Mr. Brown', 'students' => 18, 'status' => 'active'],
                ['id' => 4, 'name' => 'English Literature', 'code' => 'ENG201', 'teacher' => 'Ms. Davis', 'students' => 22, 'status' => 'inactive']
            ]
        ];
        
        return view('admin/courses', $data);
    }
    
    public function reports()
    {
        $data = [
            'title' => 'View Reports',
            'page_title' => 'System Reports',
            'description' => 'View comprehensive reports and analytics for the LMS system.',
            'stats' => [
                'total_users' => 156,
                'total_students' => 120,
                'total_teachers' => 25,
                'total_courses' => 45,
                'active_courses' => 38,
                'total_assignments' => 234,
                'pending_approvals' => 12
            ],
            'recent_activity' => [
                ['action' => 'New user registration', 'user' => 'Charlie Brown', 'time' => '2 hours ago'],
                ['action' => 'Course created', 'user' => 'Dr. Smith', 'time' => '5 hours ago'],
                ['action' => 'Assignment submitted', 'user' => 'Alice Johnson', 'time' => '1 day ago'],
                ['action' => 'Grade updated', 'user' => 'Prof. Johnson', 'time' => '2 days ago']
            ]
        ];
        
        return view('admin/reports', $data);
    }

    /**
     * Create new user
     */
    public function createUser()
    {
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $role = $this->request->getPost('role');
        $status = $this->request->getPost('status');
        $password = $this->request->getPost('password');

        // Validation
        if (!$firstName || !$lastName || !$email || !$role || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required']);
        }

        try {
            $userModel = new \App\Models\UserModel();
            
            // Check if email already exists
            $existingUser = $userModel->where('email', $email)->first();
            if ($existingUser) {
                return $this->response->setJSON(['success' => false, 'message' => 'Email already exists']);
            }

            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'role' => $role,
                'status' => $status,
                'password' => password_hash($password ?: 'password123', PASSWORD_DEFAULT)
            ];

            // Insert user
            $result = $userModel->insert($userData);
            
            if ($result) {
                return $this->response->setJSON(['success' => true, 'message' => 'User created successfully']);
            } else {
                $errors = $userModel->errors();
                $errorMessage = 'Failed to create user';
                if (!empty($errors)) {
                    $errorMessage = 'Validation errors: ' . implode(', ', $errors);
                }
                return $this->response->setJSON(['success' => false, 'message' => $errorMessage]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update existing user
     */
    public function updateUser()
    {
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $userId = $this->request->getPost('id');
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $role = $this->request->getPost('role');
        $status = $this->request->getPost('status');
        $password = $this->request->getPost('password');

        // Validation
        if (!$userId || !$firstName || !$lastName || !$email || !$role || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required']);
        }

        try {
            $userModel = new \App\Models\UserModel();
            
            // Check if email already exists for another user
            $existingUser = $userModel->where('email', $email)->where('id !=', $userId)->first();
            if ($existingUser) {
                return $this->response->setJSON(['success' => false, 'message' => 'Email already exists']);
            }

            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'role' => $role,
                'status' => $status
            ];

            // Update password if provided
            if ($password) {
                $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($userModel->update($userId, $userData)) {
                return $this->response->setJSON(['success' => true, 'message' => 'User updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update user']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete user
     */
    public function deleteUser()
    {
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $userId = $this->request->getPost('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid user ID']);
        }

        try {
            $userModel = new \App\Models\UserModel();
            
            // Prevent deletion of the current admin user
            if ($userId == session()->get('user_id')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete your own account']);
            }

            if ($userModel->delete($userId)) {
                return $this->response->setJSON(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete user']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
