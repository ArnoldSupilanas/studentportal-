<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => 3, // Bob Student
                'course_id' => 1, // Introduction to Web Development
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'enrolled',
                'progress' => 25.50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 4, // Alice Smith
                'course_id' => 1, // Introduction to Web Development
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'enrolled',
                'progress' => 45.75,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 5, // Charlie Brown
                'course_id' => 2, // Database Design and Management
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'enrolled',
                'progress' => 10.00,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 3, // Bob Student
                'course_id' => 3, // PHP Programming Fundamentals
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'enrolled',
                'progress' => 0.00,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('enrollments')->insertBatch($data);
    }
}
