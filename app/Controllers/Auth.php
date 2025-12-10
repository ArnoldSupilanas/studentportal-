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

    // Registration method
    public function register()
    {
        // Check if form was submitted (POST request)
        if ($this->request->getMethod() === 'post') {
            // Set validation rules
            $rules = [
                'name' => [
                    'rules' => 'required|min_length[3]|max_length[100]',
                    'errors' => [
                        'required' => 'Name is required',
                        'min_length' => 'Name must be at least 3 characters',
                        'max_length' => 'Name cannot exceed 100 characters'
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please provide a valid email address',
                        'is_unique' => 'This email is already registered'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 8 characters',
                        'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number'
                    ]
                ],
                'password_confirm' => [
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Password confirmation is required',
                        'matches' => 'Passwords do not match'
                    ]
                ]
            ];

            // Validate input
            if (!$this->validate($rules)) {
                // Validation failed, return to form with errors
                return view('auth/register', [
                    'validation' => $this->validator
                ]);
            }

            // Validation passed, prepare user data
            $name = $this->request->getPost('name');
            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT, ['cost' => 12]),
                'role' => 'student',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Save user to database
            if ($this->userModel->insert($userData)) {
                session()->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to('/login');
            } else {
                session()->setFlashdata('error', 'Registration failed. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        // Display registration form
        return view('auth/register');
    }

    // Login method
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        // Check if form was submitted (POST request)
        if ($this->request->getMethod() === 'POST') {
            
            // Set validation rules
            $rules = [
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email'
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]'
                ]
            ];
            
            // Validate input
            if (!$this->validate($rules)) {
                return view('auth/login', [
                    'validation' => $this->validator
                ]);
            }

            // Get form data
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Check database for user
            $user = $this->userModel->where('email', $email)->first();

            // Check if user exists and is active
            if (!$user) {
                session()->setFlashdata('error', 'Invalid email or password');
                return redirect()->to('/login')->withInput();
            }

            // Check if account is active
            if (isset($user['status']) && $user['status'] !== 'active') {
                session()->setFlashdata('error', 'Your account has been suspended. Please contact administrator.');
                return redirect()->to('/login')->withInput();
            }

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, create session
                $sessionData = [
                    'userID' => $user['id'],
                    'user_id' => $user['id'],
                    'name' => $user['first_name'] . ' ' . $user['last_name'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'is_logged_in' => true,
                    'logged_in' => true,
                    'login_time' => time()
                ];
                
                // Set session data
                session()->set($sessionData);

                // Set welcome flash message
                session()->setFlashdata('success', 'Welcome back, ' . $user['first_name'] . '!');
                
                // Redirect to unified dashboard
                return redirect()->to('/dashboard');
            } else {
                // Invalid credentials
                session()->setFlashdata('error', 'Invalid email or password');
                return redirect()->to('/login')->withInput();
            }
        }

        // Display login form
        return view('auth/login');
    }

    // Logout method
    public function logout()
    {
        // Destroy session
        session()->destroy();
        
        // Redirect to login page
        return redirect()->to('/login');
    }

    // Dashboard method - Unified dashboard with role-based content
    public function dashboard()
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        // If not logged in, redirect to login
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }
        
        // Get user role from session
        $role = session()->get('role') ?? 'student';
        
        // Fetch role-specific data from database
        $roleData = $this->getRoleSpecificData($role, $userId);
        
        // Prepare data to pass to view
        $data = [
            'is_logged_in' => true,
            'user_id' => $userId,
            'name' => session()->get('name') ?? (session()->get('first_name') . ' ' . session()->get('last_name')),
            'email' => session()->get('email') ?? 'No email',
            'role' => $role,
            'role_data' => $roleData
        ];

        return view('auth/dashboard', $data);
    }
    
    // Simple dashboard method for testing login redirect
    public function simpleDashboard()
    {
        // Mock data if no session
        $data = [
            'is_logged_in' => true,
            'user_id' => 2,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'teacher',
            'role_data' => [
                'total_students' => 3,
                'courses' => [],
                'assignments' => []
            ]
        ];

        return view('auth/dashboard', $data);
    }
    
    // Test Dashboard method (no authentication required)
    public function testDashboard()
    {
        // Mock session data for testing
        $data = [
            'is_logged_in' => true,
            'user_id' => 2,
            'name' => 'Test Teacher',
            'email' => 'instructor@lms.com',
            'role' => 'teacher',
            'role_data' => [
                'total_students' => 3,
                'courses' => [],
                'assignments' => []
            ]
        ];

        return view('auth/dashboard', $data);
    }
    
    // Helper method to fetch role-specific data
    private function getRoleSpecificData($role, $userId)
    {
        $data = [];
        
        switch ($role) {
            case 'admin':
                // Fetch admin-specific data
                $data['total_users'] = $this->userModel->countAll();
                $data['total_students'] = $this->userModel->where('role', 'student')->countAllResults();
                $data['total_teachers'] = $this->userModel->where('role', 'teacher')->countAllResults();
                $data['total_courses'] = 10; // Mock data
                $data['pending_approvals'] = 3;
                break;
                
            case 'teacher':
                // Fetch teacher-specific data
                $data['total_courses'] = 5;
                $data['total_students'] = 45;
                $data['pending_assignments'] = 3;
                $data['upcoming_classes'] = 4;
                $data['recent_submissions'] = 12;
                $data['courses'] = [
                    ['name' => 'Mathematics 101', 'students' => 25, 'progress' => 75],
                    ['name' => 'Computer Science', 'students' => 20, 'progress' => 60]
                ];
                $data['assignments'] = [
                    ['title' => 'Homework #3', 'due' => '2025-12-10', 'submissions' => 18],
                    ['title' => 'Quiz Chapter 5', 'due' => '2025-12-12', 'submissions' => 15]
                ];
                break;
                
            case 'student':
                // Fetch student-specific data
                $data['total_courses'] = 6;
                $data['completed_assignments'] = 12;
                $data['pending_assignments'] = 2;
                $data['upcoming_deadlines'] = 3;
                $data['current_grade'] = 'B+';
                $data['attendance_rate'] = '92%';
                $data['courses'] = [
                    ['name' => 'Mathematics 101', 'grade' => 'A-', 'teacher' => 'Dr. Smith'],
                    ['name' => 'English Literature', 'grade' => 'B+', 'teacher' => 'Ms. Johnson'],
                    ['name' => 'Physics', 'grade' => 'B', 'teacher' => 'Mr. Brown']
                ];
                $data['assignments'] = [
                    ['title' => 'Math Problem Set #5', 'due' => '2025-12-10', 'status' => 'completed'],
                    ['title' => 'Essay: Shakespeare', 'due' => '2025-12-12', 'status' => 'pending']
                ];
                break;
                
            default:
                $data = [];
        }
        
        return $data;
    }
}