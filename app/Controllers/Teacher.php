<?php

namespace App\Controllers;

class Teacher extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Teacher Dashboard',
            'page_title' => 'Welcome, Teacher!',
            'description' => 'Manage your courses and students from this dashboard.'
        ];
        return view('teacher_dashboard', $data);
    }
    
    public function courses()
    {
        // Default courses
        $defaultCourses = [
            ['id' => 1, 'name' => 'Mathematics 101', 'students' => 25, 'progress' => 75],
            ['id' => 2, 'name' => 'Computer Science', 'students' => 20, 'progress' => 60],
            ['id' => 3, 'name' => 'Physics 201', 'students' => 18, 'progress' => 45]
        ];
        
        // Get existing courses from file
        $coursesFile = WRITEPATH . 'created_courses.json';
        $createdCourses = [];
        
        if (file_exists($coursesFile)) {
            $jsonContent = file_get_contents($coursesFile);
            $createdCourses = json_decode($jsonContent, true) ?: [];
        }
        
        // Merge both arrays
        $allCourses = array_merge($defaultCourses, $createdCourses);
        
        $data = [
            'title' => 'My Courses',
            'page_title' => 'Course Management',
            'description' => 'Manage and view all your assigned courses here.',
            'courses' => $allCourses
        ];
        return view('teacher/courses', $data);
    }
    
    public function assignments()
    {
        $data = [
            'title' => 'Assignments',
            'page_title' => 'Assignment Management',
            'description' => 'View and manage all your assignments here.',
            'assignments' => [
                ['id' => 1, 'title' => 'Homework #3', 'course' => 'Mathematics 101', 'due' => '2025-12-10', 'submissions' => 18],
                ['id' => 2, 'title' => 'Quiz Chapter 5', 'course' => 'Computer Science', 'due' => '2025-12-12', 'submissions' => 15],
                ['id' => 3, 'title' => 'Lab Report', 'course' => 'Physics 201', 'due' => '2025-12-15', 'submissions' => 12]
            ]
        ];
        return view('teacher/assignments', $data);
    }
    
    public function grades()
    {
        $data = [
            'title' => 'Grade Students',
            'page_title' => 'Grading Management',
            'students' => [
                ['name' => 'Alice Johnson', 'course' => 'Mathematics 101', 'grade' => 'A-', 'status' => 'pending'],
                ['name' => 'Bob Smith', 'course' => 'Mathematics 101', 'grade' => 'B+', 'status' => 'pending'],
                ['name' => 'Charlie Brown', 'course' => 'Computer Science', 'grade' => 'A', 'status' => 'graded']
            ]
        ];
        return view('teacher/grades', $data);
    }
    
    public function attendance()
    {
        $data = [
            'title' => 'Attendance',
            'page_title' => 'Attendance Management',
            'classes' => [
                ['name' => 'Mathematics 101', 'date' => '2025-12-09', 'present' => 22, 'absent' => 3],
                ['name' => 'Computer Science', 'date' => '2025-12-09', 'present' => 19, 'absent' => 1],
                ['name' => 'Physics 201', 'date' => '2025-12-08', 'present' => 16, 'absent' => 2]
            ]
        ];
        return view('teacher/attendance', $data);
    }
    
    // Create Assignment method (for navigation)
    public function createAssignment()
    {
        $data = [
            'title' => 'Create Assignment',
            'page_title' => 'Create New Assignment',
            'courses' => [
                ['id' => 1, 'name' => 'Mathematics 101'],
                ['id' => 2, 'name' => 'Computer Science'],
                ['id' => 3, 'name' => 'Physics 201']
            ]
        ];
        return view('teacher/create_assignment', $data);
    }
    
    // Store Assignment method (for form submission)
    public function storeAssignment()
    {
        // Get form data
        $title = $this->request->getPost('title');
        $courseId = $this->request->getPost('course_id');
        $dueDate = $this->request->getPost('due_date');
        $points = $this->request->getPost('points');
        $description = $this->request->getPost('description');
        $instructions = $this->request->getPost('instructions');
        
        // Validate input
        if (!$title || !$courseId || !$dueDate || !$points) {
            session()->setFlashdata('error', 'Please fill in all required fields');
            return redirect()->to('/teacher/createAssignment');
        }
        
        // For now, we'll just simulate storing the assignment
        // In a real application, you would save to database here
        $assignmentData = [
            'title' => $title,
            'course_id' => $courseId,
            'due_date' => $dueDate,
            'points' => $points,
            'description' => $description,
            'instructions' => $instructions,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Set success message
        session()->setFlashdata('success', 'Assignment "' . $title . '" has been created successfully!');
        
        // Redirect to assignments list
        return redirect()->to('/teacher/assignments');
    }
    
    // Manage Course method
    public function manageCourse($courseId = null)
    {
        if (!$courseId) {
            session()->setFlashdata('error', 'Course ID is required');
            return redirect()->to('/teacher/courses');
        }
        
        // Find the course from the mock data
        $courses = [
            ['id' => 1, 'name' => 'Mathematics 101', 'students' => 25, 'progress' => 75],
            ['id' => 2, 'name' => 'Computer Science', 'students' => 20, 'progress' => 60],
            ['id' => 3, 'name' => 'Physics 201', 'students' => 18, 'progress' => 45]
        ];
        
        $course = null;
        foreach ($courses as $c) {
            if ($c['id'] == $courseId) {
                $course = $c;
                break;
            }
        }
        
        if (!$course) {
            session()->setFlashdata('error', 'Course not found');
            return redirect()->to('/teacher/courses');
        }
        
        $data = [
            'title' => 'Manage Course',
            'page_title' => 'Manage ' . $course['name'],
            'description' => 'Manage course details, assignments, and students.',
            'course' => $course,
            'students' => [
                ['name' => 'Alice Johnson', 'email' => 'alice@email.com', 'grade' => 'A-'],
                ['name' => 'Bob Smith', 'email' => 'bob@email.com', 'grade' => 'B+'],
                ['name' => 'Charlie Brown', 'email' => 'charlie@email.com', 'grade' => 'A']
            ],
            'assignments' => [
                ['title' => 'Homework #3', 'due' => '2025-12-10', 'submissions' => 18],
                ['title' => 'Quiz Chapter 5', 'due' => '2025-12-12', 'submissions' => 15]
            ]
        ];
        
        return view('teacher/manage_course', $data);
    }
    
    // Add New Course method
    public function addCourse()
    {
        if ($this->request->getPost('course_name')) {
            // Process form submission
            $courseName = $this->request->getPost('course_name');
            $courseDescription = $this->request->getPost('course_description');
            
            // Validate input
            if (!$courseName) {
                session()->setFlashdata('error', 'Course name is required');
                return redirect()->to('teacher/addCourse');
            }
            
            // Get existing courses from file
            $coursesFile = WRITEPATH . 'created_courses.json';
            $createdCourses = [];
            
            if (file_exists($coursesFile)) {
                $jsonContent = file_get_contents($coursesFile);
                $createdCourses = json_decode($jsonContent, true) ?: [];
            }
            
            // Create new course with unique ID
            $newCourse = [
                'id' => count($createdCourses) + 100,
                'name' => $courseName,
                'description' => $courseDescription,
                'students' => 0,
                'progress' => 0
            ];
            
            // Add to courses array
            $createdCourses[] = $newCourse;
            
            // Store in file
            file_put_contents($coursesFile, json_encode($createdCourses));
            
            // Set success message
            session()->setFlashdata('success', 'Course "' . $courseName . '" has been created successfully!');
            
            return redirect()->to('teacher/courses');
        }
        
        // Show the add course form
        $data = [
            'title' => 'Add New Course',
            'page_title' => 'Create New Course',
            'description' => 'Fill in the details below to create a new course.'
        ];
        return view('teacher/add_course', $data);
    }
    
    // View Students method (for navigation)
    public function students()
    {
        $data = [
            'title' => 'View Students',
            'page_title' => 'Student Management',
            'description' => 'Manage and view enrolled students for your courses',
            'students' => [
                ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@email.com', 'course' => 'Mathematics 101', 'grade' => 'A-'],
                ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@email.com', 'course' => 'Mathematics 101', 'grade' => 'B+'],
                ['id' => 3, 'name' => 'Charlie Brown', 'email' => 'charlie@email.com', 'course' => 'Computer Science', 'grade' => 'A'],
                ['id' => 4, 'name' => 'Diana Prince', 'email' => 'diana@email.com', 'course' => 'Physics 201', 'grade' => 'B'],
                ['id' => 5, 'name' => 'Edward Norton', 'email' => 'edward@email.com', 'course' => 'Computer Science', 'grade' => 'A-']
            ]
        ];
        return view('teacher/students', $data);
    }
    
    // Grade Book method (for navigation)
    public function gradeBook()
    {
        $data = [
            'title' => 'Grade Book',
            'page_title' => 'Grade Management',
            'courses' => [
                [
                    'name' => 'Mathematics 101',
                    'students' => [
                        ['name' => 'Alice Johnson', 'assignments' => [85, 92, 88], 'average' => 88.3],
                        ['name' => 'Bob Smith', 'assignments' => [78, 85, 82], 'average' => 81.7]
                    ]
                ],
                [
                    'name' => 'Computer Science',
                    'students' => [
                        ['name' => 'Charlie Brown', 'assignments' => [95, 98, 92], 'average' => 95.0],
                        ['name' => 'Edward Norton', 'assignments' => [88, 91, 89], 'average' => 89.3]
                    ]
                ]
            ]
        ];
        return view('teacher/grade_book', $data);
    }
    
    public function viewStudent($studentId)
    {
        // In a real application, you would fetch student data from a database
        $students = [
            ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@email.com', 'course' => 'Mathematics 101', 'grade' => 'A-', 'status' => 'Active', 'details' => 'Alice is a diligent student with a strong grasp of mathematical concepts.'],
            ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@email.com', 'course' => 'Mathematics 101', 'grade' => 'B+', 'status' => 'Active', 'details' => 'Bob shows good potential but sometimes struggles with complex problem-solving.'],
            ['id' => 3, 'name' => 'Charlie Brown', 'email' => 'charlie@email.com', 'course' => 'Computer Science', 'grade' => 'A', 'status' => 'Active', 'details' => 'Charlie is an exceptional student in Computer Science, always eager to learn new technologies.'],
            ['id' => 4, 'name' => 'Diana Prince', 'email' => 'diana@email.com', 'course' => 'Physics 201', 'grade' => 'B', 'status' => 'Active', 'details' => 'Diana has a solid understanding of physics principles, but could improve her lab report writing.'],
            ['id' => 5, 'name' => 'Edward Norton', 'email' => 'edward@email.com', 'course' => 'Computer Science', 'grade' => 'A-', 'status' => 'Active', 'details' => 'Edward is a consistent performer in Computer Science, with a keen interest in software development.']
        ];

        $student = null;
        foreach ($students as $s) {
            if ($s['id'] == $studentId) {
                $student = $s;
                break;
            }
        }

        if ($student) {
            $data = [
                'title' => 'View Student',
                'page_title' => 'Student Details',
                'description' => 'Detailed information about the student',
                'student' => $student
            ];
            return view('teacher/view_student', $data);
        } else {
            return redirect()->to(base_url('teacher/students'))->with('error', 'Student not found.');
        }
    }
    
    public function emailStudent($studentId)
    {
        // In a real application, you would fetch student data from a database
        $students = [
            ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@email.com', 'course' => 'Mathematics 101'],
            ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@email.com', 'course' => 'Mathematics 101'],
            ['id' => 3, 'name' => 'Charlie Brown', 'email' => 'charlie@email.com', 'course' => 'Computer Science'],
            ['id' => 4, 'name' => 'Diana Prince', 'email' => 'diana@email.com', 'course' => 'Physics 201'],
            ['id' => 5, 'name' => 'Edward Norton', 'email' => 'edward@email.com', 'course' => 'Computer Science']
        ];

        $student = null;
        foreach ($students as $s) {
            if ($s['id'] == $studentId) {
                $student = $s;
                break;
            }
        }

        if ($student) {
            $data = [
                'title' => 'Email Student',
                'page_title' => 'Send Email to Student',
                'description' => 'Compose and send an email to the student',
                'student' => $student
            ];
            return view('teacher/email_student', $data);
        } else {
            return redirect()->to(base_url('teacher/students'))->with('error', 'Student not found.');
        }
    }
    
    public function editStudent($studentId)
    {
        // In a real application, you would fetch student data from a database
        $students = [
            ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@email.com', 'course' => 'Mathematics 101', 'grade' => 'A-', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@email.com', 'course' => 'Mathematics 101', 'grade' => 'B+', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Charlie Brown', 'email' => 'charlie@email.com', 'course' => 'Computer Science', 'grade' => 'A', 'status' => 'Active'],
            ['id' => 4, 'name' => 'Diana Prince', 'email' => 'diana@email.com', 'course' => 'Physics 201', 'grade' => 'B', 'status' => 'Active'],
            ['id' => 5, 'name' => 'Edward Norton', 'email' => 'edward@email.com', 'course' => 'Computer Science', 'grade' => 'A-', 'status' => 'Active']
        ];

        $student = null;
        foreach ($students as $s) {
            if ($s['id'] == $studentId) {
                $student = $s;
                break;
            }
        }

        if ($student) {
            $data = [
                'title' => 'Edit Student',
                'page_title' => 'Edit Student Information',
                'description' => 'Update student details and information',
                'student' => $student
            ];
            return view('teacher/edit_student', $data);
        } else {
            return redirect()->to(base_url('teacher/students'))->with('error', 'Student not found.');
        }
    }
    
    public function sendEmail($studentId)
    {
        // Get form data
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $ccTeacher = $this->request->getPost('cc_teacher');
        
        // Validate input
        if (!$subject || !$message) {
            session()->setFlashdata('error', 'Subject and message are required');
            return redirect()->to('/teacher/emailStudent/' . $studentId);
        }
        
        // In a real application, you would send the email here
        // For now, we'll just simulate sending the email
        
        // Get student data for the success message
        $students = [
            ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@email.com'],
            ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@email.com'],
            ['id' => 3, 'name' => 'Charlie Brown', 'email' => 'charlie@email.com'],
            ['id' => 4, 'name' => 'Diana Prince', 'email' => 'diana@email.com'],
            ['id' => 5, 'name' => 'Edward Norton', 'email' => 'edward@email.com']
        ];
        
        $student = null;
        foreach ($students as $s) {
            if ($s['id'] == $studentId) {
                $student = $s;
                break;
            }
        }
        
        if ($student) {
            // Simulate email sending
            $emailData = [
                'to' => $student['email'],
                'subject' => $subject,
                'message' => $message,
                'cc_teacher' => $ccTeacher,
                'sent_at' => date('Y-m-d H:i:s')
            ];
            
            // Log the email (in a real app, you'd use email service)
            log_message('info', 'Email sent to ' . $student['email'] . ' with subject: ' . $subject);
            
            session()->setFlashdata('success', 'Email successfully sent to ' . $student['name'] . ' (' . $student['email'] . ')');
        } else {
            session()->setFlashdata('error', 'Student not found');
        }
        
        return redirect()->to('/teacher/students');
    }
    
    public function updateStudent($studentId)
    {
        // Get form data
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $course = $this->request->getPost('course');
        $grade = $this->request->getPost('grade');
        $status = $this->request->getPost('status');
        $phone = $this->request->getPost('phone');
        $emergencyContact = $this->request->getPost('emergency_contact');
        $notes = $this->request->getPost('notes');
        $attendance = $this->request->getPost('attendance');
        $participation = $this->request->getPost('participation');
        $assignmentsCompleted = $this->request->getPost('assignments_completed');
        
        // Validate required fields
        if (!$name || !$email || !$course || !$grade || !$status) {
            session()->setFlashdata('error', 'Please fill in all required fields');
            return redirect()->to('/teacher/editStudent/' . $studentId);
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session()->setFlashdata('error', 'Please enter a valid email address');
            return redirect()->to('/teacher/editStudent/' . $studentId);
        }
        
        // In a real application, you would update the database here
        // For now, we'll just simulate the update
        
        $updateData = [
            'id' => $studentId,
            'name' => $name,
            'email' => $email,
            'course' => $course,
            'grade' => $grade,
            'status' => $status,
            'phone' => $phone,
            'emergency_contact' => $emergencyContact,
            'notes' => $notes,
            'attendance' => $attendance,
            'participation' => $participation,
            'assignments_completed' => $assignmentsCompleted,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Log the update (in a real app, you'd update the database)
        log_message('info', 'Student updated: ' . json_encode($updateData));
        
        session()->setFlashdata('success', 'Student information updated successfully for ' . $name);
        
        return redirect()->to('/teacher/students');
    }
}