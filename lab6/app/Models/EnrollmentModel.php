<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrollment_date',
        'status',
        'progress',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer|greater_than[0]',
        'course_id' => 'required|integer|greater_than[0]',
        'enrollment_date' => 'required|valid_date[Y-m-d H:i:s]',
        'status' => 'in_list[enrolled,completed,dropped]',
        'progress' => 'numeric|greater_than_equal_to[0]|less_than_equal_to[100]'
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
     * Enroll a user in a course
     *
     * @param array $data Contains user_id, course_id, and enrollment_date
     * @return bool|int Returns the enrollment ID on success, false on failure
     */
    public function enrollUser($data)
    {
        $userId = (int) $data['user_id'];
        $courseId = (int) $data['course_id'];
        $existing = $this->where('user_id', $userId)
                         ->where('course_id', $courseId)
                         ->first();

        if ($existing) {
            if (isset($existing['status']) && $existing['status'] === 'enrolled') {
                return (int) $existing['id'];
            }
            $updateData = [
                'status' => 'enrolled',
                'enrollment_date' => $data['enrollment_date'] ?? date('Y-m-d H:i:s'),
                'progress' => 0.00
            ];
            $this->update($existing['id'], $updateData);
            return (int) $existing['id'];
        }

        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => $data['enrollment_date'] ?? date('Y-m-d H:i:s'),
            'status' => 'enrolled',
            'progress' => 0.00
        ];

        return $this->insert($enrollmentData);
    }

    /**
     * Get all courses a user is enrolled in
     *
     * @param int $user_id The user ID
     * @return array Array of enrollment records with course details
     */
    public function getUserEnrollments($user_id)
    {
        return $this->select('enrollments.*, courses.title, courses.description, courses.instructor_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $user_id)
                    ->where('enrollments.status', 'enrolled')
                    ->findAll();
    }

    /**
     * Check if a user is already enrolled in a specific course
     *
     * @param int $user_id The user ID
     * @param int $course_id The course ID
     * @return bool True if enrolled, false otherwise
     */
    public function isAlreadyEnrolled($user_id, $course_id)
    {
        $enrollment = $this->where('user_id', $user_id)
                          ->where('course_id', $course_id)
                          ->where('status', 'enrolled')
                          ->first();

        return !empty($enrollment);
    }

    /**
     * Get enrollment details for a specific user and course
     *
     * @param int $user_id The user ID
     * @param int $course_id The course ID
     * @return array|null Enrollment record or null if not found
     */
    public function getEnrollment($user_id, $course_id)
    {
        return $this->where('user_id', $user_id)
                    ->where('course_id', $course_id)
                    ->first();
    }

    /**
     * Update enrollment progress
     *
     * @param int $user_id The user ID
     * @param int $course_id The course ID
     * @param float $progress The progress percentage (0-100)
     * @return bool True on success, false on failure
     */
    public function updateProgress($user_id, $course_id, $progress)
    {
        return $this->where('user_id', $user_id)
                    ->where('course_id', $course_id)
                    ->set(['progress' => $progress])
                    ->update();
    }

    /**
     * Mark enrollment as completed
     *
     * @param int $user_id The user ID
     * @param int $course_id The course ID
     * @return bool True on success, false on failure
     */
    public function completeEnrollment($user_id, $course_id)
    {
        return $this->where('user_id', $user_id)
                    ->where('course_id', $course_id)
                    ->set(['status' => 'completed', 'progress' => 100.00])
                    ->update();
    }

    /**
     * Drop/remove enrollment
     *
     * @param int $user_id The user ID
     * @param int $course_id The course ID
     * @return bool True on success, false on failure
     */
    public function dropEnrollment($user_id, $course_id)
    {
        return $this->where('user_id', $user_id)
                    ->where('course_id', $course_id)
                    ->set(['status' => 'dropped'])
                    ->update();
    }

    /**
     * Get enrollment statistics for dashboard
     *
     * @return array Array of enrollment statistics
     */
    public function getEnrollmentStats()
    {
        $builder = $this->db->table('enrollments');
        
        $stats = [
            'total_enrollments' => $builder->countAllResults(),
        ];
        
        // Reset builder for each query
        $builder = $this->db->table('enrollments');
        $stats['active_enrollments'] = $builder->where('status', 'enrolled')->countAllResults();
        
        $builder = $this->db->table('enrollments');
        $stats['completed_enrollments'] = $builder->where('status', 'completed')->countAllResults();
        
        $builder = $this->db->table('enrollments');
        $stats['dropped_enrollments'] = $builder->where('status', 'dropped')->countAllResults();

        return $stats;
    }

    /**
     * Get all enrollments with user and course details
     *
     * @param string|null $status Filter by status
     * @param int|null $limit Limit number of results
     * @param int $offset Offset for pagination
     * @return array Array of enrollment records
     */
    public function getAllEnrollments($status = null, $limit = null, $offset = 0)
    {
        $builder = $this->select('enrollments.*, users.first_name, users.last_name, users.email, courses.title as course_title')
                        ->join('users', 'users.id = enrollments.user_id')
                        ->join('courses', 'courses.id = enrollments.course_id');

        if ($status) {
            $builder->where('enrollments.status', $status);
        }

        if ($limit) {
            return $builder->limit($limit, $offset)->findAll();
        }

        return $builder->findAll();
    }

    /**
     * Search enrollments with filters
     *
     * @param string $search_term Search term
     * @param string|null $status Filter by status
     * @param int|null $limit Limit number of results
     * @param int $offset Offset for pagination
     * @return array Array of matching enrollment records
     */
    public function searchEnrollments($search_term, $status = null, $limit = null, $offset = 0)
    {
        $builder = $this->select('enrollments.*, users.first_name, users.last_name, users.email, courses.title as course_title')
                        ->join('users', 'users.id = enrollments.user_id')
                        ->join('courses', 'courses.id = enrollments.course_id')
                        ->groupStart()
                            ->like('users.first_name', $search_term)
                            ->orLike('users.last_name', $search_term)
                            ->orLike('users.email', $search_term)
                            ->orLike('courses.title', $search_term)
                        ->groupEnd();

        if ($status) {
            $builder->where('enrollments.status', $status);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Count search results
     *
     * @param string $search_term Search term
     * @param string|null $status Filter by status
     * @return int Number of matching records
     */
    public function countSearchResults($search_term, $status = null)
    {
        $builder = $this->db->table('enrollments')
                        ->join('users', 'users.id = enrollments.user_id')
                        ->join('courses', 'courses.id = enrollments.course_id')
                        ->groupStart()
                            ->like('users.first_name', $search_term)
                            ->orLike('users.last_name', $search_term)
                            ->orLike('users.email', $search_term)
                            ->orLike('courses.title', $search_term)
                        ->groupEnd();

        if ($status) {
            $builder->where('enrollments.status', $status);
        }

        return $builder->countAllResults();
    }

    /**
     * Count enrollments by status
     *
     * @param string|null $status Filter by status
     * @return int Number of enrollments
     */
    public function countEnrollments($status = null)
    {
        $builder = $this->db->table('enrollments');
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        return $builder->countAllResults();
    }
}
