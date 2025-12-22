<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['course_id', 'file_name', 'file_path', 'created_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'course_id' => 'required|integer|greater_than[0]',
        'file_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[255]',
        'created_at' => 'required|valid_date[Y-m-d H:i:s]'
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
     * Insert a new material record
     *
     * @param array $data Contains course_id, file_name, file_path, and optionally created_at
     * @return bool|int Returns the material ID on success, false on failure
     */
    public function insertMaterial($data)
    {
        // Set default created_at if not provided
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        // Validate required fields
        if (!isset($data['course_id']) || !isset($data['file_name']) || !isset($data['file_path'])) {
            return false;
        }

        try {
            return $this->insert($data);
        } catch (\Exception $e) {
            // Log error if needed
            log_message('error', 'Error inserting material: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all materials for a specific course
     *
     * @param int $course_id The course ID
     * @return array Array of material records
     */
    public function getMaterialsByCourse($course_id)
    {
        if (!is_numeric($course_id) || $course_id <= 0) {
            return [];
        }

        try {
            return $this->where('course_id', $course_id)
                        ->orderBy('created_at', 'DESC')
                        ->findAll();
        } catch (\Exception $e) {
            // Log error if needed
            log_message('error', 'Error getting materials for course: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a single material by ID
     *
     * @param int $material_id The material ID
     * @return array|null Material record or null if not found
     */
    public function getMaterial($material_id)
    {
        if (!is_numeric($material_id) || $material_id <= 0) {
            return null;
        }

        try {
            return $this->find($material_id);
        } catch (\Exception $e) {
            log_message('error', 'Error getting material: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a material record
     *
     * @param int $material_id The material ID
     * @param array $data The data to update
     * @return bool True on success, false on failure
     */
    public function updateMaterial($material_id, $data)
    {
        if (!is_numeric($material_id) || $material_id <= 0 || empty($data)) {
            return false;
        }

        try {
            return $this->update($material_id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating material: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a material record
     *
     * @param int $material_id The material ID
     * @return bool True on success, false on failure
     */
    public function deleteMaterial($material_id)
    {
        if (!is_numeric($material_id) || $material_id <= 0) {
            return false;
        }

        try {
            return $this->delete($material_id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting material: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Count materials for a specific course
     *
     * @param int $course_id The course ID
     * @return int Number of materials
     */
    public function countMaterialsByCourse($course_id)
    {
        if (!is_numeric($course_id) || $course_id <= 0) {
            return 0;
        }

        try {
            return $this->where('course_id', $course_id)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting materials for course: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get materials with course information
     *
     * @param int|null $course_id Optional course ID to filter by
     * @return array Array of materials with course details
     */
    public function getMaterialsWithCourse($course_id = null)
    {
        try {
            $builder = $this->select('materials.*, courses.title as course_title')
                           ->join('courses', 'courses.id = materials.course_id');

            if ($course_id && is_numeric($course_id)) {
                $builder->where('materials.course_id', $course_id);
            }

            return $builder->orderBy('materials.created_at', 'DESC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting materials with course: ' . $e->getMessage());
            return [];
        }
    }
}
