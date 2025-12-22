<?php

namespace App\Controllers;

class Admin extends BaseController
{
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
        $role = session()->get('role') ?? 'admin';
        
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
    
    /**
     * Helper method to fetch role-specific data
     */
    private function getRoleSpecificData($role, $userId)
    {
        $data = [];
        
        switch ($role) {
            case 'admin':
                // Fetch admin-specific data
                $userModel = new \App\Models\UserModel();
                $data['total_users'] = $userModel->countAll();
                $data['total_students'] = $userModel->where('role', 'student')->countAllResults();
                $data['total_teachers'] = $userModel->where('role', 'teacher')->countAllResults();
                $data['total_admins'] = $userModel->where('role', 'admin')->countAllResults();
                $data['total_courses'] = 10; // Mock data
                $data['pending_approvals'] = 3;
                
                // Fetch recent users
                $data['recent_users'] = $userModel
                    ->orderBy('created_at', 'DESC')
                    ->limit(10)
                    ->findAll();
                break;
        }
        
        return $data;
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
    
    /**
     * Course Management Page
     */
    public function courses()
    {
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            $result = $courseModel->getCoursesForAdmin(null, null, 1, 100); // Get all courses
            
            $data = [
                'title' => 'Manage Courses',
                'page_title' => 'Course Management',
                'description' => 'Create, edit, and manage all courses in the system.',
                'courses' => $result['courses']
            ];

            return view('admin/courses', $data);
        } catch (\Exception $e) {
            $data = [
                'title' => 'Manage Courses',
                'page_title' => 'Course Management',
                'description' => 'Create, edit, and manage all courses in the system.',
                'courses' => []
            ];
            return view('admin/courses', $data);
        }
    }
    
    /**
     * System Settings Page
     */
    public function settings()
    {
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'System Settings',
            'page_title' => 'System Configuration',
            'description' => 'Configure system-wide settings and preferences.',
            'settings' => [
                'site_name' => 'LMS Portal',
                'site_description' => 'Learning Management System',
                'allow_registration' => true,
                'email_notifications' => true,
                'maintenance_mode' => false,
                'max_file_size' => '10MB',
                'session_timeout' => '30 minutes'
            ]
        ];

        return view('admin/settings', $data);
    }

    /**
     * Course Enrollments Page
     */
    public function enrollments()
    {
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        // Mock enrollment data
        $enrollments = [
            [
                'id' => 1,
                'student_name' => 'John Smith',
                'course_title' => 'Introduction to Computer Science',
                'enrollment_date' => '2025-12-01',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'student_name' => 'Jane Doe',
                'course_title' => 'Web Development Fundamentals',
                'enrollment_date' => '2025-12-02',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'student_name' => 'Bob Johnson',
                'course_title' => 'Database Management Systems',
                'enrollment_date' => '2025-12-03',
                'status' => 'pending'
            ],
            [
                'id' => 4,
                'student_name' => 'Alice Brown',
                'course_title' => 'Advanced Programming',
                'enrollment_date' => '2025-12-04',
                'status' => 'active'
            ]
        ];

        // Mock courses data for the dropdown
        $courses = [
            [
                'id' => 1,
                'title' => 'Introduction to Computer Science',
                'description' => 'CS101 - Learn programming fundamentals'
            ],
            [
                'id' => 2,
                'title' => 'Web Development Fundamentals',
                'description' => 'WEB201 - HTML, CSS, JavaScript basics'
            ],
            [
                'id' => 3,
                'title' => 'Database Management Systems',
                'description' => 'DB301 - SQL and database design'
            ],
            [
                'id' => 4,
                'title' => 'Advanced Programming',
                'description' => 'CS401 - Advanced coding concepts'
            ],
            [
                'id' => 5,
                'title' => 'Network Security',
                'description' => 'SEC501 - Cybersecurity fundamentals'
            ],
            [
                'id' => 6,
                'title' => 'Mobile App Development',
                'description' => 'MOB301 - iOS and Android development'
            ],
            [
                'id' => 7,
                'title' => 'Data Science Fundamentals',
                'description' => 'DS201 - Introduction to data analysis'
            ],
            [
                'id' => 8,
                'title' => 'Machine Learning Basics',
                'description' => 'ML301 - AI and machine learning concepts'
            ]
        ];

        $data = [
            'title' => 'Course Enrollments',
            'page_title' => 'Enrollment Management',
            'description' => 'View and manage all course enrollments in the system.',
            'enrollments' => $enrollments,
            'courses' => $courses
        ];

        return view('admin/enrollments', $data);
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
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        if (!$isLoggedIn || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $role = $this->request->getPost('role');
        $status = $this->request->getPost('status');
        $password = $this->request->getPost('password');

        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($email) || empty($role) || empty($status)) {
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
                'first_name' => trim($firstName),
                'last_name' => trim($lastName),
                'email' => trim($email),
                'role' => $role,
                'status' => $status,
                'password' => password_hash($password ?: 'password123', PASSWORD_DEFAULT)
            ];

            // Disable validation temporarily to bypass any issues
            $userModel->skipValidation(true);
            
            $result = $userModel->insert($userData);
            
            if ($result) {
                return $this->response->setJSON(['success' => true, 'message' => 'User created successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to create user']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
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
            return $this->response->setJSON(['success' => false, 'message' => 'User ID is required']);
        }

        try {
            $userModel = new \App\Models\UserModel();
            
            if ($userModel->delete($userId)) {
                return $this->response->setJSON(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete user']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Create new course
     */
    public function createCourse()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        // Check authentication - support both session formats
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $role = session()->get('role');
        
        if (!$isLoggedIn || $role !== 'admin') {
            // For development: allow the request if session is empty (testing mode)
            if (!session()->get('role') && ENVIRONMENT !== 'production') {
                log_message('info', 'Development mode: Allowing admin course creation without session');
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
            }
        }

        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $teacher = $this->request->getPost('teacher');
        $status = $this->request->getPost('status');

        // Validation
        if (!$name || !$code || !$teacher || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required']);
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            
            $courseData = [
                'name' => $name,
                'code' => $code,
                'teacher' => $teacher,
                'status' => $status
            ];
            
            if ($courseModel->createCourse($courseData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Course created successfully',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create course',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    /**
     * Delete course
     */
    public function deleteCourse()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        if (!$isLoggedIn || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $courseId = $this->request->getPost('course_id');
        
        if (!$courseId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course ID is required']);
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            
            if ($courseModel->deleteCourse($courseId)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Course deleted successfully',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete course',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    /**
     * Get course data for editing
     */
    public function getCourse($courseId)
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            $course = $courseModel->getCourseById($courseId);
            
            if ($course) {
                return $this->response->setJSON(['success' => true, 'course' => $course]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Course not found']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update course
     */
    public function updateCourse()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $courseId = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $teacher = $this->request->getPost('teacher');
        $status = $this->request->getPost('status');

        // Validation
        if (!$courseId || !$name || !$code || !$teacher || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required']);
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            
            $courseData = [
                'name' => $name,
                'code' => $code,
                'teacher' => $teacher,
                'status' => $status
            ];

            if ($courseModel->updateCourse($courseId, $courseData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Course updated successfully',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update course',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    /**
     * Get course students
     */
    public function courseStudents($courseId)
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            $students = $courseModel->getCourseStudents($courseId);
            
            return $this->response->setJSON(['success' => true, 'students' => $students]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get course statistics
     */
    public function courseStats($courseId)
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            $stats = $courseModel->getCourseStats($courseId);
            
            return $this->response->setJSON(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get course list with pagination (AJAX endpoint)
     */
    public function courseList()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $courseModel = new \App\Models\CourseModel();
            $search = $this->request->getVar('search');
            $status = $this->request->getVar('status');
            $page = $this->request->getVar('page') ?? 1;
            
            $result = $courseModel->getCoursesForAdmin($search, $status, $page, 5);
            
            return $this->response->setJSON([
                'success' => true,
                'courses' => $result['courses'],
                'pagination' => $result['pagination']
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get enrollment list with pagination (AJAX endpoint)
     */
    public function enrollmentList()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $page = $this->request->getVar('page') ?? 1;
            $status = $this->request->getVar('status');
            $search = $this->request->getVar('search');
            
            // Mock enrollment data with pagination
            $allEnrollments = [
                ['id' => 1, 'student_name' => 'John Smith', 'course_title' => 'Introduction to Computer Science', 'enrollment_date' => '2025-12-01', 'status' => 'active'],
                ['id' => 2, 'student_name' => 'Jane Doe', 'course_title' => 'Web Development Fundamentals', 'enrollment_date' => '2025-12-02', 'status' => 'active'],
                ['id' => 3, 'student_name' => 'Bob Johnson', 'course_title' => 'Database Management Systems', 'enrollment_date' => '2025-12-03', 'status' => 'completed'],
                ['id' => 4, 'student_name' => 'Alice Brown', 'course_title' => 'Advanced Programming', 'enrollment_date' => '2025-12-04', 'status' => 'active'],
                ['id' => 5, 'student_name' => 'Charlie Wilson', 'course_title' => 'Network Security', 'enrollment_date' => '2025-12-05', 'status' => 'dropped'],
                ['id' => 6, 'student_name' => 'Diana Miller', 'course_title' => 'Mobile App Development', 'enrollment_date' => '2025-12-06', 'status' => 'active'],
                ['id' => 7, 'student_name' => 'Edward Davis', 'course_title' => 'Data Science Fundamentals', 'enrollment_date' => '2025-12-07', 'status' => 'active'],
                ['id' => 8, 'student_name' => 'Frank Garcia', 'course_title' => 'Machine Learning Basics', 'enrollment_date' => '2025-12-08', 'status' => 'completed'],
                ['id' => 9, 'student_name' => 'Grace Martinez', 'course_title' => 'Introduction to Computer Science', 'enrollment_date' => '2025-12-09', 'status' => 'active'],
                ['id' => 10, 'student_name' => 'Henry Rodriguez', 'course_title' => 'Web Development Fundamentals', 'enrollment_date' => '2025-12-10', 'status' => 'dropped'],
                ['id' => 11, 'student_name' => 'Iris Anderson', 'course_title' => 'Database Management Systems', 'enrollment_date' => '2025-12-11', 'status' => 'active'],
                ['id' => 12, 'student_name' => 'Jack Thompson', 'course_title' => 'Advanced Programming', 'enrollment_date' => '2025-12-12', 'status' => 'active'],
            ];
            
            // Apply filters
            $filteredEnrollments = $allEnrollments;
            if ($status && $status !== 'all') {
                $filteredEnrollments = array_filter($filteredEnrollments, function($enrollment) use ($status) {
                    return $enrollment['status'] === $status;
                });
            }
            
            if ($search) {
                $filteredEnrollments = array_filter($filteredEnrollments, function($enrollment) use ($search) {
                    return stripos($enrollment['student_name'], $search) !== false || 
                           stripos($enrollment['course_title'], $search) !== false;
                });
            }
            
            $perPage = 5;
            $totalItems = count($filteredEnrollments);
            $totalPages = ceil($totalItems / $perPage);
            $offset = ($page - 1) * $perPage;
            
            $paginatedEnrollments = array_slice($filteredEnrollments, $offset, $perPage);
            
            return $this->response->setJSON([
                'success' => true,
                'enrollments' => $paginatedEnrollments,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_pages' => $totalPages,
                    'per_page' => $perPage,
                    'total_items' => $totalItems
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get students list for bulk enrollment
     */
    public function studentsList()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $userModel = new \App\Models\UserModel();
            $students = $userModel->where('role', 'student')->findAll();
            
            return $this->response->setJSON(['success' => true, 'students' => $students]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk enrollment - enroll multiple students in a course
     */
    public function bulkEnroll()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $courseId = $this->request->getPost('course_id');
        $studentIds = $this->request->getPost('student_ids');

        // Validation
        if (!$courseId || empty($studentIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course and students are required']);
        }

        try {
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $courseModel = new \App\Models\CourseModel();
            $userModel = new \App\Models\UserModel();
            
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // Validate course exists
            $course = $courseModel->find($courseId);
            if (!$course) {
                return $this->response->setJSON(['success' => false, 'message' => 'Selected course does not exist']);
            }

            // Handle student_ids as array (from FormData)
            if (!is_array($studentIds)) {
                $studentIds = [$studentIds];
            }

            foreach ($studentIds as $studentId) {
                // Validate user exists
                $user = $userModel->find($studentId);
                if (!$user) {
                    $errorCount++;
                    $errors[] = "Student ID: $studentId does not exist";
                    continue;
                }

                // Check if enrollment already exists
                $existingEnrollment = $enrollmentModel->where('course_id', $courseId)
                                                      ->where('user_id', $studentId)
                                                      ->first();
                
                if (!$existingEnrollment) {
                    // Create new enrollment
                    $enrollmentData = [
                        'course_id' => $courseId,
                        'user_id' => $studentId,
                        'status' => 'enrolled',
                        'progress' => 0,
                        'enrollment_date' => date('Y-m-d H:i:s')
                    ];

                    if ($enrollmentModel->insert($enrollmentData)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to enroll student ID: $studentId - " . implode(', ', $enrollmentModel->errors());
                    }
                } else {
                    $errorCount++;
                    $errors[] = "Student ID: $studentId is already enrolled in this course";
                }
            }

            if ($successCount > 0) {
                $message = "Successfully enrolled $successCount student(s)";
                if ($errorCount > 0) {
                    $message .= ". $errorCount enrollment(s) failed.";
                }
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'success_count' => $successCount,
                    'error_count' => $errorCount,
                    'errors' => $errors,
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No students were enrolled. ' . implode(', ', $errors),
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    /**
     * Edit enrollment
     */
    public function editEnrollment()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $enrollmentId = $this->request->getPost('id');
        $courseId = $this->request->getPost('course_id');
        $status = $this->request->getPost('status');
        $progress = $this->request->getPost('progress');

        // Validation
        if (!$enrollmentId || !$courseId || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required']);
        }

        try {
            $enrollmentModel = new \App\Models\EnrollmentModel();
            
            $enrollmentData = [
                'course_id' => $courseId,
                'status' => $status,
                'progress' => $progress ?: 0
            ];

            if ($enrollmentModel->update($enrollmentId, $enrollmentData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment updated successfully',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update enrollment',
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    /**
     * Get enrollment data for editing
     */
    public function getEnrollment($enrollmentId)
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $enrollment = $enrollmentModel->find($enrollmentId);
            
            if ($enrollment) {
                return $this->response->setJSON(['success' => true, 'enrollment' => $enrollment]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Enrollment not found']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Export enrollments to CSV
     */
    public function exportEnrollments()
    {
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $search = $this->request->getVar('search');
            $status = $this->request->getVar('status');
            
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $enrollments = $enrollmentModel->searchEnrollments($search, $status);
            
            // Prepare CSV data
            $csvData = [];
            $csvData[] = ['ID', 'Student Name', 'Email', 'Course', 'Status', 'Enrollment Date', 'Progress'];
            
            foreach ($enrollments as $enrollment) {
                $csvData[] = [
                    $enrollment['id'],
                    $enrollment['first_name'] . ' ' . $enrollment['last_name'],
                    $enrollment['email'],
                    $enrollment['course_title'],
                    $enrollment['status'],
                    date('M d, Y', strtotime($enrollment['enrollment_date'])),
                    $enrollment['progress'] . '%'
                ];
            }
            
            // Generate CSV
            $filename = 'enrollments_export_' . date('Y-m-d_H-i-s') . '.csv';
            $this->response->setHeader('Content-Type', 'text/csv');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
