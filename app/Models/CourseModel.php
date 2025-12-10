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
        'description' => 'required',
        'instructor_id' => 'required|integer|greater_than[0]',
        'status' => 'in_list[active,inactive,archived]'
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
        return $this->where('status', 'active')->findAll();
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
                    ->where('courses.status', 'active')
                    ->findAll();
    }
}
