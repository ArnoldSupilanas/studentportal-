<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['title', 'description', 'instructor_id', 'status', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'description' => 'max_length[1000]',
        'instructor_id' => 'integer|greater_than[0]',
        'status' => 'in_list[draft,published,archived]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get active courses
     */
    public function getActiveCourses()
    {
        return $this->whereIn('status', ['active', 'published'])->findAll();
    }

    /**
     * Get courses by instructor
     */
    public function getCoursesByInstructor($instructorId)
    {
        return $this->where('instructor_id', $instructorId)->findAll();
    }

    /**
     * Get course with instructor details
     */
    public function getCourseWithInstructor($courseId)
    {
        return $this->select('courses.*, users.first_name, users.last_name, users.email as instructor_email')
                    ->join('users', 'users.id = courses.instructor_id')
                    ->where('courses.id', $courseId)
                    ->first();
    }

    /**
     * Get all courses with instructor details
     */
    public function getAllCoursesWithInstructors()
    {
        return $this->select('courses.*, users.first_name, users.last_name')
                    ->join('users', 'users.id = courses.instructor_id')
                    ->whereIn('courses.status', ['active', 'published'])
                    ->findAll();
    }

    /**
     * Get active/published courses with instructor name and enrolled students count
     */
    public function getActiveCoursesWithStats()
    {
        return $this->select("courses.*, CONCAT(users.first_name, ' ', users.last_name) as instructor_name, (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = courses.id AND e.status = 'enrolled') as students_count")
                    ->join('users', 'users.id = courses.instructor_id', 'left')
                    ->whereIn('courses.status', ['active', 'published'])
                    ->findAll();
    }

    /**
     * Get courses for admin view with real database operations
     */
    public function getCoursesForAdmin($search = null, $status = null, $page = 1, $perPage = 5)
    {
        $builder = $this->select('courses.*, CONCAT(users.first_name, " ", users.last_name) as teacher_name, courses.course_code')
                     ->join('users', 'users.id = courses.instructor_id', 'left');
        
        // Exclude specific course IDs (10, 11, 12, 13, 14, 15, 16)
        $builder->whereNotIn('courses.id', [10, 11, 12, 13, 14, 15, 16]);
        
        // Apply filters
        if ($status && $status !== 'all') {
            $builder->where('courses.status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                    ->like('courses.title', $search)
                    ->orLike('courses.description', $search)
                    ->orLike('users.first_name', $search)
                    ->orLike('users.last_name', $search)
                    ->groupEnd();
        }

        // Get total count for pagination
        $totalItems = $builder->countAllResults(false);
        
        // Get paginated results
        $courses = $builder->limit($perPage, ($page - 1) * $perPage)
                           ->get()
                           ->getResultArray();
        
        // Format courses data
        $formattedCourses = [];
        foreach ($courses as $course) {
            // Get student count for this course
            $studentCount = $this->db->table('enrollments')
                                    ->where('course_id', $course['id'])
                                    ->where('status', 'enrolled')
                                    ->countAllResults();
            
            $formattedCourses[] = [
                'id' => $course['id'],
                'name' => $course['title'],
                'code' => $course['course_code'] ?? strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $course['title']), 0, 6)),
                'teacher' => $course['teacher_name'] ?? 'Not Assigned',
                'students' => $studentCount,
                'status' => $course['status']
            ];
        }

        $totalPages = ceil($totalItems / $perPage);
        
        return [
            'courses' => $formattedCourses,
            'pagination' => [
                'current_page' => (int)$page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_items' => $totalItems
            ]
        ];
    }

    /**
     * Get course by ID for admin
     */
    public function getCourseById($courseId)
    {
        $course = $this->select('courses.*, CONCAT(users.first_name, " ", users.last_name) as teacher_name')
                     ->join('users', 'users.id = courses.instructor_id', 'left')
                     ->where('courses.id', $courseId)
                     ->first();
        
        if ($course) {
            // Generate a course code from title
            $courseCode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $course['title']), 0, 6));
            
            return [
                'id' => $course['id'],
                'name' => $course['title'],
                'code' => $courseCode,
                'teacher' => $course['teacher_name'] ?? 'Not Assigned',
                'status' => $course['status']
            ];
        }
        
        return null;
    }

    /**
     * Create new course
     */
    public function createCourse($data)
    {
        // Normalize status to DB values
        $status = $data['status'] ?? 'draft';
        if ($status === 'active') {
            $status = 'published';
        } elseif ($status === 'inactive') {
            $status = 'draft';
        }
        if (!in_array($status, ['draft', 'published', 'archived'])) {
            $status = 'draft';
        }

        // Prepare data for insertion
        $courseData = [
            'title' => $data['name'],
            'description' => ($data['description'] ?? ($data['name'] . ' - Course Description')),
            'instructor_id' => $data['instructor_id'] ?? 1, // TODO: map teacher to user id
            'status' => $status
        ];

        return $this->insert($courseData);
    }

    /**
     * Update course
     */
    public function updateCourse($courseId, $data)
    {
        // Normalize status to DB values
        $status = $data['status'] ?? 'draft';
        if ($status === 'active') {
            $status = 'published';
        } elseif ($status === 'inactive') {
            $status = 'draft';
        }
        if (!in_array($status, ['draft', 'published', 'archived'])) {
            $status = 'draft';
        }

        // Prepare data for update
        $courseData = [
            'title' => $data['name'],
            'description' => ($data['description'] ?? ($data['name'] . ' - Course Description')),
            'status' => $status
        ];

        return $this->update($courseId, $courseData);
    }

    /**
     * Delete course
     */
    public function deleteCourse($courseId)
    {
        return $this->delete($courseId);
    }

    /**
     * Get course students
     */
    public function getCourseStudents($courseId)
    {
        return $this->db->table('enrollments')
                        ->select('enrollments.*, users.first_name, users.last_name, users.email')
                        ->join('users', 'users.id = enrollments.user_id')
                        ->where('enrollments.course_id', $courseId)
                        ->get()
                        ->getResultArray();
    }

    /**
     * Get course statistics
     */
    public function getCourseStats($courseId)
    {
        $totalStudents = $this->db->table('enrollments')
                                ->where('course_id', $courseId)
                                ->countAllResults();
        
        $activeStudents = $this->db->table('enrollments')
                                  ->where('course_id', $courseId)
                                  ->where('status', 'enrolled')
                                  ->countAllResults();
        
        $completedStudents = $this->db->table('enrollments')
                                     ->where('course_id', $courseId)
                                     ->where('status', 'completed')
                                     ->countAllResults();
        
        $completionRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100, 1) : 0;
        
        // Mock average grade for now
        $averageGrade = 'B+';
        
        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'completed_students' => $completedStudents,
            'completion_rate' => $completionRate,
            'average_grade' => $averageGrade
        ];
    }
}
