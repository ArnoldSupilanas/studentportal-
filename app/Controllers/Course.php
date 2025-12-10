<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Course extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
    }

    /**
     * Handle AJAX enrollment request
     */
    public function enroll()
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to enroll in courses'
            ]);
        }

        // Get course_id from POST request
        $courseId = $this->request->getPost('course_id');
        
        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID'
            ]);
        }

        // Check if course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found'
            ]);
        }

        // Check if user is already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course'
            ]);
        }

        // Prepare enrollment data
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        // Insert new enrollment record
        try {
            $enrollmentId = $this->enrollmentModel->enrollUser($enrollmentData);
            
            if ($enrollmentId) {
                // Get course details for response
                $courseDetails = $this->courseModel->find($courseId);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Successfully enrolled in ' . $course['title'],
                    'enrollment_id' => $enrollmentId,
                    'course_data' => [
                        'course_id' => $courseId,
                        'title' => $courseDetails['title'],
                        'description' => $courseDetails['description']
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to enroll in course'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while enrolling: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle AJAX unenrollment request
     */
    public function unenroll()
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to unenroll from courses'
            ]);
        }

        // Get course_id from POST request
        $courseId = $this->request->getPost('course_id');
        
        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID'
            ]);
        }

        // Check if user is enrolled
        if (!$this->enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not enrolled in this course'
            ]);
        }

        // Drop enrollment
        try {
            $result = $this->enrollmentModel->dropEnrollment($userId, $courseId);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Successfully unenrolled from course'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to unenroll from course'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while unenrolling: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get user's enrolled courses (AJAX endpoint)
     */
    public function getEnrolledCourses()
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to view enrolled courses'
            ]);
        }

        try {
            $enrollments = $this->enrollmentModel->getUserEnrollments($userId);
            
            return $this->response->setJSON([
                'success' => true,
                'courses' => $enrollments
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while fetching enrolled courses: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display available courses for enrollment
     */
    public function index()
    {
        // Authorization check
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            return redirect()->to('/login');
        }

        // Get all available courses
        $courses = $this->courseModel->findAll();
        
        // Get user's enrollments to mark already enrolled courses
        $userEnrollments = $this->enrollmentModel->getUserEnrollments($userId);
        $enrolledCourseIds = array_column($userEnrollments, 'course_id');

        $data = [
            'title' => 'Available Courses',
            'page_title' => 'Course Catalog',
            'description' => 'Browse and enroll in available courses.',
            'courses' => $courses,
            'enrolled_course_ids' => $enrolledCourseIds
        ];
        
        return view('courses/index', $data);
    }
}
