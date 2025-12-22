<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Materials extends BaseController
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    /**
     * Display file upload form and handle file upload process
     *
     * @param int $course_id The course ID
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function upload($course_id)
    {
        // Check if user is logged in and has appropriate role
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        $role = session()->get('role');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access materials');
            return redirect()->to('/login');
        }

        // Only admin and teacher roles can upload materials
        if (!in_array($role, ['admin', 'teacher'])) {
            session()->setFlashdata('error', 'Access denied. Only admin and teacher roles can upload materials.');
            return redirect()->to('/dashboard');
        }

        // Validate course ID
        if (!is_numeric($course_id) || $course_id <= 0) {
            session()->setFlashdata('error', 'Invalid course ID');
            return redirect()->to('/dashboard');
        }

        // Check if course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            // Create a dummy course if it doesn't exist (for testing purposes)
            $course = [
                'id' => $course_id,
                'title' => 'Course ' . $course_id,
                'description' => 'Test course for materials upload',
                'course_code' => 'COURSE' . $course_id
            ];
        }

        // Handle file upload
        if ($this->request->getMethod() === 'POST') {
            log_message('info', 'Upload POST request received for course_id: ' . $course_id);
            log_message('info', 'FILES data: ' . json_encode($_FILES));
            log_message('info', 'POST data: ' . json_encode($_POST));
            
            // Load CodeIgniter's Validation Library
            $validation = \Config\Services::validation();
            
            // Set validation rules
            $rules = [
                'material_file' => [
                    'label' => 'Material File',
                    'rules' => 'uploaded[material_file]|max_size[material_file,10240]|ext_in[material_file,pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif,zip,rar]',
                    'errors' => [
                        'uploaded' => 'Please select a file to upload.',
                        'max_size' => 'File size must be less than 10MB.',
                        'ext_in' => 'Invalid file type. Allowed types: pdf, doc, docx, ppt, pptx, txt, jpg, jpeg, png, gif, zip, rar'
                    ]
                ]
            ];
            
            if ($this->validate($rules)) {
                log_message('info', 'Validation passed');
                // Load CodeIgniter's File Uploading Library
                $uploadedFile = $this->request->getFile('material_file');
                log_message('info', 'File object created: ' . ($uploadedFile ? 'true' : 'false'));
                
                if ($uploadedFile && $uploadedFile->isValid()) {
                    log_message('info', 'File is valid, name: ' . $uploadedFile->getName() . ', size: ' . $uploadedFile->getSize());
                    // Configure upload preferences
                    $uploadPath = WRITEPATH . 'uploads/materials/' . $course_id;
                    
                    // Create upload directory if it doesn't exist
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    // Generate unique filename to prevent conflicts
                    $originalName = $uploadedFile->getName();
                    $fileExtension = $uploadedFile->getExtension();
                    $newFileName = time() . '_' . url_title($originalName, '-', true) . '.' . $fileExtension;
                    
                    // Perform file upload using CodeIgniter's move method
                    if ($uploadedFile->move($uploadPath, $newFileName, true)) {
                        // Prepare data for database
                        $materialData = [
                            'course_id' => $course_id,
                            'file_name' => $originalName,
                            'file_path' => 'uploads/materials/' . $course_id . '/' . $newFileName,
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        // Save to database using MaterialModel
                        try {
                            $materialId = $this->materialModel->insertMaterial($materialData);
                            
                            if ($materialId) {
                                session()->setFlashdata('success', 'Material "' . $originalName . '" uploaded successfully!');
                                return redirect()->to('/materials/upload/' . $course_id);
                            } else {
                                session()->setFlashdata('error', 'Failed to save material record to database.');
                                // Delete uploaded file if database insert failed
                                $uploadedFilePath = $uploadPath . '/' . $newFileName;
                                if (file_exists($uploadedFilePath)) {
                                    unlink($uploadedFilePath);
                                }
                            }
                        } catch (\Exception $e) {
                            log_message('error', 'Database error: ' . $e->getMessage());
                            session()->setFlashdata('error', 'Database error: ' . $e->getMessage());
                            // Delete uploaded file if database insert failed
                            $uploadedFilePath = $uploadPath . '/' . $newFileName;
                            if (file_exists($uploadedFilePath)) {
                                unlink($uploadedFilePath);
                            }
                        }
                    } else {
                        session()->setFlashdata('error', 'Failed to upload file. Please try again.');
                    }
                } else {
                    // Get upload error details
                    $error = $uploadedFile->getError();
                    $errorString = $uploadedFile->getErrorString();
                    log_message('error', 'File upload error: ' . $error . ' - ' . $errorString);
                    session()->setFlashdata('error', 'Failed to upload file: ' . $errorString);
                }
            } else {
                // Get validation errors
                $validationErrors = $this->validator->getErrors();
                log_message('error', 'Validation errors: ' . json_encode($validationErrors));
                session()->setFlashdata('error', 'Validation failed: ' . implode(', ', $validationErrors));
            }
        }

        // Get existing materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);
        log_message('info', 'Materials retrieved for course ' . $course_id . ': ' . count($materials) . ' items');

        $data = [
            'title' => 'Upload Materials - ' . $course['title'],
            'page_title' => 'Upload Course Materials',
            'description' => 'Upload and manage materials for ' . $course['title'],
            'course' => $course,
            'materials' => $materials,
            'role' => $role,
            'validation' => \Config\Services::validation()
        ];

        return view('materials/upload', $data);
    }

    /**
     * Handle deletion of material record and associated file
     *
     * @param int $material_id The material ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($material_id)
    {
        // Check if user is logged in and has appropriate role
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        $role = session()->get('role');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access materials');
            return redirect()->to('/login');
        }

        // Only admin and teacher roles can delete materials
        if (!in_array($role, ['admin', 'teacher'])) {
            session()->setFlashdata('error', 'Access denied. Only admin and teacher roles can delete materials.');
            return redirect()->to('/dashboard');
        }

        // Validate material ID
        if (!is_numeric($material_id) || $material_id <= 0) {
            session()->setFlashdata('error', 'Invalid material ID');
            return redirect()->to('/dashboard');
        }

        // Get material details
        $material = $this->materialModel->getMaterial($material_id);
        if (!$material) {
            session()->setFlashdata('error', 'Material not found');
            return redirect()->to('/dashboard');
        }

        // Delete file from server
        $filePath = WRITEPATH . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete material record from database
        if ($this->materialModel->deleteMaterial($material_id)) {
            session()->setFlashdata('success', 'Material deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete material record.');
        }

        // Redirect back to upload page
        return redirect()->to('/materials/upload/' . $material['course_id']);
    }

    /**
     * Handle file download for enrolled students
     *
     * @param int $material_id The material ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\RedirectResponse
     */
    public function download($material_id)
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        $role = session()->get('role');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access materials');
            return redirect()->to('/login');
        }

        // Validate material ID
        if (!is_numeric($material_id) || $material_id <= 0) {
            session()->setFlashdata('error', 'Invalid material ID');
            return redirect()->to('/dashboard');
        }

        // Get material details from database using the MaterialModel
        $material = $this->materialModel->getMaterial($material_id);
        
        if (!$material) {
            session()->setFlashdata('error', 'Material not found');
            return redirect()->to('/dashboard');
        }

        // Get course details for the material
        $course = $this->courseModel->find($material['course_id']);
        if (!$course) {
            session()->setFlashdata('error', 'Associated course not found');
            return redirect()->to('/dashboard');
        }

        // Check if user has access to this material
        $hasAccess = false;
        
        // Admin and teacher have access to all materials
        if (in_array($role, ['admin', 'teacher'])) {
            $hasAccess = true;
        }
        // Students must be enrolled in the course
        elseif ($role === 'student') {
            $hasAccess = $this->enrollmentModel->isAlreadyEnrolled($userId, $material['course_id']);
        }

        if (!$hasAccess) {
            session()->setFlashdata('error', 'Access denied. You must be enrolled in this course to download materials.');
            return redirect()->to('/dashboard');
        }

        // Construct the full file path
        $filePath = WRITEPATH . $material['file_path'];
        
        // Enhanced file validation
        if (!file_exists($filePath)) {
            log_message('error', 'Material file not found: ' . $filePath);
            session()->setFlashdata('error', 'File not found on server');
            return redirect()->to('/dashboard');
        }

        if (!is_readable($filePath)) {
            log_message('error', 'Material file not readable: ' . $filePath);
            session()->setFlashdata('error', 'File cannot be read');
            return redirect()->to('/dashboard');
        }

        // Additional security checks
        $realPath = realpath($filePath);
        $allowedPaths = [
            realpath(WRITEPATH . 'uploads/materials/'),
        ];
        
        $isAllowedPath = false;
        foreach ($allowedPaths as $allowedPath) {
            if (strpos($realPath, $allowedPath) === 0) {
                $isAllowedPath = true;
                break;
            }
        }
        
        if (!$isAllowedPath) {
            log_message('error', 'Attempted directory traversal: ' . $filePath);
            session()->setFlashdata('error', 'Access denied');
            return redirect()->to('/dashboard');
        }

        // Get file information
        $fileInfo = pathinfo($filePath);
        $fileName = $material['file_name'] ?? $fileInfo['basename'];
        $fileSize = filesize($filePath);
        $mimeType = $this->getMimeType($filePath);

        try {
            // Log the download for audit purposes
            log_message('info', 'Material downloaded: Material ID ' . $material_id . 
                       ' by User ID ' . $userId . ' (Role: ' . $role . ') from Course ID ' . $material['course_id']);

            // Use CodeIgniter's download helper for secure file download
            return $this->response->download($filePath, $fileName, true)
                ->setHeader('Content-Type', $mimeType)
                ->setHeader('Content-Length', $fileSize)
                ->setHeader('Cache-Control', 'no-cache, must-revalidate')
                ->setHeader('Pragma', 'no-cache')
                ->setHeader('Expires', '0')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        } catch (\Exception $e) {
            log_message('error', 'Download error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Error downloading file. Please try again.');
            return redirect()->to('/dashboard');
        }
    }

    /**
     * Get MIME type for a file
     *
     * @param string $filePath Path to the file
     * @return string MIME type
     */
    private function getMimeType($filePath)
    {
        // Try to get MIME type from fileinfo extension
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            
            if ($mimeType !== false) {
                return $mimeType;
            }
        }

        // Fallback to extension-based MIME type detection
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'txt' => 'text/plain',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Display materials for a specific course (for students)
     *
     * @param int $course_id The course ID
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function view($course_id)
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        $role = session()->get('role');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access materials');
            return redirect()->to('/login');
        }

        // Validate course ID
        if (!is_numeric($course_id) || $course_id <= 0) {
            session()->setFlashdata('error', 'Invalid course ID');
            return redirect()->to('/dashboard');
        }

        // Check if course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            // Create a dummy course if it doesn't exist (for testing purposes)
            $course = [
                'id' => $course_id,
                'title' => 'Course ' . $course_id,
                'description' => 'Test course for materials view',
                'course_code' => 'COURSE' . $course_id
            ];
        }

        // Check if user has access to this course
        $hasAccess = false;
        
        // Admin and teacher have access to all courses
        if (in_array($role, ['admin', 'teacher'])) {
            $hasAccess = true;
        }
        // Students must be enrolled in the course
        elseif ($role === 'student') {
            $hasAccess = $this->enrollmentModel->isAlreadyEnrolled($userId, $course_id);
        }

        if (!$hasAccess) {
            session()->setFlashdata('error', 'Access denied. You must be enrolled in this course to view materials.');
            return redirect()->to('/dashboard');
        }

        // Get materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        $data = [
            'title' => 'Course Materials - ' . $course['title'],
            'page_title' => 'Course Materials',
            'description' => 'View and download materials for ' . $course['title'],
            'course' => $course,
            'materials' => $materials,
            'role' => $role
        ];

        return view('materials/view', $data);
    }

    /**
     * List all materials for admin/teacher
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        // Check if user is logged in and has appropriate role
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        $role = session()->get('role');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access materials');
            return redirect()->to('/login');
        }

        // Only admin and teacher roles can view all materials
        if (!in_array($role, ['admin', 'teacher'])) {
            session()->setFlashdata('error', 'Access denied. Only admin and teacher roles can view all materials.');
            return redirect()->to('/dashboard');
        }

        // Get all materials with course information
        $materials = $this->materialModel->getMaterialsWithCourse();

        $data = [
            'title' => 'All Course Materials',
            'page_title' => 'Course Materials Management',
            'description' => 'Manage all course materials',
            'materials' => $materials,
            'role' => $role
        ];

        return view('materials/index', $data);
    }

    /**
     * Display materials for students (download-only view)
     *
     * @param int $course_id The course ID
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function studentMaterials($course_id)
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('is_logged_in') || session()->get('logged_in');
        $userId = session()->get('userID') ?? session()->get('user_id');
        $role = session()->get('role');
        
        if (!$isLoggedIn || !$userId) {
            session()->setFlashdata('error', 'Please login to access materials');
            return redirect()->to('/login');
        }

        // Only students can access this view
        if ($role !== 'student') {
            session()->setFlashdata('error', 'Access denied. This view is for students only.');
            return redirect()->to('/dashboard');
        }

        // Validate course ID
        if (!is_numeric($course_id) || $course_id <= 0) {
            session()->setFlashdata('error', 'Invalid course ID');
            return redirect()->to('/dashboard');
        }

        // Check if course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found');
            return redirect()->to('/dashboard');
        }

        // Check if student is enrolled in this course
        if (!$this->enrollmentModel->isAlreadyEnrolled($userId, $course_id)) {
            session()->setFlashdata('error', 'You must be enrolled in this course to access materials.');
            return redirect()->to('/dashboard');
        }

        // Get materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        $data = [
            'title' => 'Course Materials - ' . $course['title'],
            'page_title' => 'Course Materials',
            'description' => 'Download materials for ' . $course['title'],
            'course' => $course,
            'materials' => $materials,
            'role' => $role
        ];

        return view('materials/student_view', $data);
    }
}
