<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Student extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
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
        
        // Get enrolled courses
        $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
        
        // Get available courses (courses user is not enrolled in)
        $allCourses = $this->courseModel->getActiveCourses();
        $enrolledCourseIds = array_column($enrolledCourses, 'course_id');
        
        $availableCourses = [];
        foreach ($allCourses as $course) {
            if (!in_array($course['id'], $enrolledCourseIds)) {
                $availableCourses[] = $course;
            }
        }
        
        $data = [
            'title' => 'Student Dashboard',
            'page_title' => 'Welcome, Student!',
            'description' => 'View your courses, assignments, and grades from this dashboard.',
            'enrolled_courses' => $enrolledCourses,
            'available_courses' => $availableCourses
        ];
        
        return view('student_dashboard', $data);
    }
    
    public function courses()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'My Courses',
            'page_title' => 'My Courses',
            'description' => 'View and manage your enrolled courses.'
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
}
