<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CourseCreator extends Controller
{
    public function create()
    {
        try {
            // Get database connection using the same method as other controllers
            $db = \Config\Database::connect();
            
            // Check if courses exist
            $check = $db->query("SELECT COUNT(*) as count FROM courses");
            $count = $check->getRow()->count;
            
            echo "Current courses count: $count\n";
            
            if ($count == 0) {
                // Insert test courses
                $courses = [
                    [
                        'title' => 'Introduction to Web Development',
                        'description' => 'Learn fundamentals of web development including HTML, CSS, and JavaScript basics',
                        'instructor_id' => 1,
                        'status' => 'active',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'title' => 'Database Design and Management',
                        'description' => 'Comprehensive guide to database design principles and SQL',
                        'instructor_id' => 2,
                        'status' => 'active',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'title' => 'Advanced JavaScript Programming',
                        'description' => 'Master modern JavaScript concepts including ES6+, async programming, and frameworks',
                        'instructor_id' => 1,
                        'status' => 'active',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'title' => 'PHP Web Development',
                        'description' => 'Build dynamic web applications using PHP and modern frameworks',
                        'instructor_id' => 2,
                        'status' => 'active',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                ];
                
                // Insert courses using the database connection
                foreach ($courses as $course) {
                    $db->table('courses')->insert($course);
                }
                
                echo "Created " . count($courses) . " courses successfully!\n";
                echo "Courses added to database. Refresh dashboard to see available courses.\n";
            } else {
                echo "Courses already exist in database.\n";
            }
            
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
