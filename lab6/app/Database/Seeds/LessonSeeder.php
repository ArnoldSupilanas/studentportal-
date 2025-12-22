<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'course_id' => 1, // Introduction to Web Development
                'title' => 'HTML Basics',
                'content' => 'Learn the fundamentals of HTML including tags, attributes, and document structure.',
                'lesson_order' => 1,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 1, // Introduction to Web Development
                'title' => 'CSS Styling',
                'content' => 'Master CSS for styling web pages including selectors, properties, and layouts.',
                'lesson_order' => 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 1, // Introduction to Web Development
                'title' => 'JavaScript Fundamentals',
                'content' => 'Introduction to JavaScript programming including variables, functions, and DOM manipulation.',
                'lesson_order' => 3,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 2, // Database Design and Management
                'title' => 'Database Concepts',
                'content' => 'Learn fundamental database concepts including tables, relationships, and normalization.',
                'lesson_order' => 1,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 2, // Database Design and Management
                'title' => 'SQL Queries',
                'content' => 'Master SQL for querying databases including SELECT, INSERT, UPDATE, and DELETE operations.',
                'lesson_order' => 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('lessons')->insertBatch($data);
    }
}
