<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript.',
                'course_code' => 'WEBDEV',
                'instructor_id' => 2, // Jane Instructor
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Database Design and Management',
                'description' => 'Comprehensive course on database design, SQL, and database management systems.',
                'course_code' => 'DBDESIGN',
                'instructor_id' => 2, // Jane Instructor
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'PHP Programming Fundamentals',
                'description' => 'Learn PHP programming from basics to advanced concepts including OOP and frameworks.',
                'course_code' => 'PHPFUND',
                'instructor_id' => 2, // Jane Instructor
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
