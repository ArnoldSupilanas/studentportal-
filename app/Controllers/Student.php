<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\MaterialModel;
use App\Models\NotificationModel;

class Student extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $materialModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->materialModel = new MaterialModel();
        $this->notificationModel = new NotificationModel();
    }
    public function enrollmentDashboard()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access the enrollment dashboard');
            return redirect()->to('/login');
        }
        
        // Pass notification data to view
        $this->passNotificationData();
        
        $role = session()->get('role') ?? 'student';
        if ($role !== 'student') {
            session()->setFlashdata('error', 'Access denied. Student role required.');
            return redirect()->to('/quick-login');
        }
        
        // Get real courses data from database
        $enrollmentModel = new EnrollmentModel();
        $courseModel = new CourseModel();
        
        // Get enrolled courses for this student
        $enrolledCourses = $enrollmentModel->getUserEnrollments($userId);
        
        // Get all available courses
        $allCourses = $courseModel->findAll();
        $enrolledCourseIds = [];
        // Filter available courses (all courses are available since enrolled courses is empty)
        $availableCourses = $allCourses;
        
        $data = [
            'enrolled_courses' => $enrolledCourses,
            'available_courses' => $availableCourses,
            'user_name' => (session()->get('first_name') ?? 'Student') . ' ' . (session()->get('last_name') ?? ''),
            'unread_notification_count' => $this->getUnreadNotificationCount()
        ];
        
        return view('student/enrollment_dashboard', $data);
    }

    public function dashboard()
    {
        // Authorization check - ensure user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access the student dashboard');
            return redirect()->to('/login');
        }
        
        // Check if user has student role
        $role = session()->get('role') ?? 'student';
        if ($role !== 'student') {
            session()->setFlashdata('error', 'Access Denied: Student access required');
            return redirect()->to('/dashboard');
        }
        
        // Pass notification data to view
        $this->passNotificationData();
        
        // Get user's enrolled courses using EnrollmentModel
        $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
        
        // Get all available courses from CourseModel
        $allCourses = $this->courseModel->findAll();
        
        // Filter out already enrolled courses from available courses
        $enrolledCourseIds = array_column($enrolledCourses, 'course_id');
        $availableCourses = [];
        
        foreach ($allCourses as $course) {
            if (!in_array($course['id'], $enrolledCourseIds)) {
                $availableCourses[] = $course;
            }
        }
        
        $data = [
            'enrolled_courses' => $enrolledCourses,
            'available_courses' => $availableCourses,
            'role' => $role,
            'name' => (session()->get('first_name') ?? 'Student') . ' ' . (session()->get('last_name') ?? ''),
            'email' => session()->get('email') ?? '',
            'is_logged_in' => $isLoggedIn,
            'unread_notification_count' => $this->getUnreadNotificationCount()
        ];
        
        return view('auth/dashboard', $data);
    }
    
    public function courses()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return redirect()->to('/login');
        }
        
        // Get real course and enrollment data
        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        
        // Get enrolled courses for this student
        $enrolledCourses = $enrollmentModel->getUserEnrollments($userId);
        
        // Get all available courses with real data
        $courses = $courseModel->getCoursesForAdmin(null, null, 1, 100);
        $availableCourses = $courses['courses'];
        
        // Convert 'name' field to 'title' for view compatibility
        $formattedCourses = [];
        foreach ($availableCourses as $course) {
            $formattedCourses[] = [
                'id' => $course['id'],
                'title' => $course['name'], // Convert name to title
                'code' => $course['code'],
                'description' => 'Course description available', // Add description
                'teacher' => $course['teacher'],
                'students' => $course['students'],
                'status' => $course['status']
            ];
        }
        
        $data = [
            'title' => 'My Courses',
            'page_title' => 'My Courses',
            'description' => 'View and manage your enrolled courses.',
            'courses' => $formattedCourses,
            'enrolled_courses' => $enrolledCourses
        ];
        
        return view('student_courses', $data);
    }
    
    public function assignments()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Assignments',
            'page_title' => 'My Assignments',
            'description' => 'View and submit your assignments.'
        ];
        
        return view('student_assignments', $data);
    }
    
    public function grades()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Grades',
            'page_title' => 'My Grades',
            'description' => 'View your academic performance and grades.'
        ];
        
        return view('student_grades', $data);
    }
    
    public function calendar()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Academic Calendar',
            'page_title' => 'Academic Calendar',
            'description' => 'View important dates and deadlines.',
            'events' => [
                ['date' => '2025-12-10', 'title' => 'Math Homework Due', 'type' => 'assignment'],
                ['date' => '2025-12-12', 'title' => 'English Essay Due', 'type' => 'assignment'],
                ['date' => '2025-12-15', 'title' => 'Science Project Due', 'type' => 'project'],
                ['date' => '2025-12-20', 'title' => 'Final Exams Start', 'type' => 'exam']
            ]
        ];
        
        return view('student_calendar', $data);
    }
    
    public function materials()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return redirect()->to('/login');
        }
        
        // Get user's enrolled courses
        $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
        
        $courseMaterials = [];
        $totalMaterials = 0;
        $recentMaterials = 0;
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-1 week'));
        
        foreach ($enrolledCourses as $enrollment) {
            $courseId = $enrollment['course_id'];
            
            // Get course details
            $course = $this->courseModel->find($courseId);
            if (!$course) continue;
            
            // Get materials for this course
            $materials = $this->materialModel->getMaterialsByCourse($courseId);
            
            $courseMaterials[$courseId] = [
                'course' => $course,
                'materials' => $materials
            ];
            
            $totalMaterials += count($materials);
            
            // Count recent materials (uploaded in last week)
            foreach ($materials as $material) {
                if ($material['created_at'] > $oneWeekAgo) {
                    $recentMaterials++;
                }
            }
        }
        
        $data = [
            'title' => 'My Materials',
            'page_title' => 'My Course Materials',
            'description' => 'Access and download materials from your enrolled courses',
            'course_materials' => $courseMaterials,
            'total_courses' => count($enrolledCourses),
            'total_materials' => $totalMaterials,
            'recent_materials' => $recentMaterials
        ];
        
        return view('materials/dashboard', $data);
    }
}
