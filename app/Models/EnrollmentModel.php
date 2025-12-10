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
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date', 'status', 'progress'];

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
        'progress' => 'numeric|greater_than_equal[0]|less_than_equal[100]'
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
        // Set default values
        $enrollmentData = [
            'user_id' => $data['user_id'],
            'course_id' => $data['course_id'],
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
}
