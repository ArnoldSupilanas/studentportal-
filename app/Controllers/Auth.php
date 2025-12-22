<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
    }

    // Quick login method for debugging
    public function quickLogin()
    {
        // Handle logout
        if ($this->request->getGet('logout')) {
            session()->destroy();
            return redirect()->to('/quick-login');
        }

        // Handle login POST
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            // Simple authentication for testing
            if ($email === 'student@lms.com' && $password === 'password') {
                session()->set([
                    'is_logged_in' => true,
                    'logged_in' => true,
                    'userID' => 3,
                    'user_id' => 3,
                    'role' => 'student',
                    'first_name' => 'Bob',
                    'last_name' => 'Student',
                    'email' => $email
                ]);
                return redirect()->to('/auth/dashboard');
            } elseif ($email === 'admin@lms.com' && $password === 'password') {
                session()->set([
                    'is_logged_in' => true,
                    'logged_in' => true,
                    'userID' => 1,
                    'user_id' => 1,
                    'role' => 'admin',
                    'first_name' => 'John',
                    'last_name' => 'Admin',
                    'email' => $email
                ]);
                return redirect()->to('/enrollment-dashboard');
            }
        }

        // Show login form
        return view('auth/quick_login');
    }

    // Registration method
    public function register()
    {
        // Check if form was submitted (POST request)
        if ($this->request->getMethod() === 'post') {
            // Rate limiting: Check if too many registration attempts
            $regAttempts = session()->get('reg_attempts') ?? 0;
            $lastRegTime = session()->get('last_reg_time') ?? 0;
            $currentTime = time();
            
            // Limit to 3 registration attempts per hour
            if ($regAttempts >= 3 && ($currentTime - $lastRegTime) < 3600) {
                session()->setFlashdata('error', 'Too many registration attempts. Please try again later.');
                return redirect()->to('/register')->withInput();
            }
            
            // Reset attempts if hour has passed
            if ($regAttempts >= 3 && ($currentTime - $lastRegTime) >= 3600) {
                session()->remove('reg_attempts');
                session()->remove('last_reg_time');
                $regAttempts = 0;
            }
            
            // Set validation rules
            $rules = [
                'name' => [
                    'rules' => 'required|min_length[3]|max_length[100]|alpha_space',
                    'errors' => [
                        'required' => 'Name is required',
                        'min_length' => 'Name must be at least 3 characters',
                        'max_length' => 'Name cannot exceed 100 characters',
                        'alpha_space' => 'Name can only contain letters and spaces'
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email|is_unique[users.email]|max_length[255]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please provide a valid email address',
                        'is_unique' => 'This email is already registered',
                        'max_length' => 'Email cannot exceed 255 characters'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|max_length[255]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 8 characters',
                        'max_length' => 'Password cannot exceed 255 characters',
                        'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character'
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
                // Increment failed registration attempts
                session()->set('reg_attempts', $regAttempts + 1);
                session()->set('last_reg_time', $currentTime);
                
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
                // Reset registration attempts on success
                session()->remove('reg_attempts');
                session()->remove('last_reg_time');
                
                session()->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to('/login');
            } else {
                // Increment failed registration attempts
                session()->set('reg_attempts', $regAttempts + 1);
                session()->set('last_reg_time', $currentTime);
                
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
            
            // Rate limiting: Check if too many login attempts
            $loginAttempts = session()->get('login_attempts') ?? 0;
            $lastAttemptTime = session()->get('last_attempt_time') ?? 0;
            $currentTime = time();
            
            // Lock out after 5 failed attempts for 15 minutes
            if ($loginAttempts >= 5 && ($currentTime - $lastAttemptTime) < 900) {
                session()->setFlashdata('error', 'Too many failed login attempts. Please try again in 15 minutes.');
                return redirect()->to('/login')->withInput();
            }
            
            // Reset attempts if lockout period has passed
            if ($loginAttempts >= 5 && ($currentTime - $lastAttemptTime) >= 900) {
                session()->remove('login_attempts');
                session()->remove('last_attempt_time');
                $loginAttempts = 0;
            }
            
            // Set validation rules
            $rules = [
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|max_length[255]'
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[1]|max_length[255]'
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
                // Increment failed login attempts
                session()->set('login_attempts', $loginAttempts + 1);
                session()->set('last_attempt_time', $currentTime);
                
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
                // Password is correct, reset login attempts
                session()->remove('login_attempts');
                session()->remove('last_attempt_time');
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
                // Invalid credentials, increment failed attempts
                session()->set('login_attempts', $loginAttempts + 1);
                session()->set('last_attempt_time', $currentTime);
                
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
        
        // Add enrollment data for students
        if ($role === 'student') {
            $data['enrolled_courses'] = $this->enrollmentModel->getUserEnrollments($userId);
            
            // Get all available active courses with instructor and students count
            $data['available_courses'] = $this->courseModel->getActiveCoursesWithStats();
            
            // Debug: Log available courses count and data
            log_message('debug', 'Available courses count: ' . count($data['available_courses']));
            log_message('debug', 'User ID: ' . $userId . ', Role: ' . $role);
        }

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
                $data['total_admins'] = $this->userModel->where('role', 'admin')->countAllResults();
                $data['total_courses'] = 10; // Mock data
                $data['pending_approvals'] = 3;
                
                // Fetch recent users
                $data['recent_users'] = $this->userModel
                    ->orderBy('created_at', 'DESC')
                    ->limit(10)
                    ->findAll();
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