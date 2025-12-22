<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'lesson_id' => 1, // HTML Basics
                'question' => 'What does HTML stand for?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['HyperText Markup Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language', 'HyperText Modern Language']),
                'correct_answer' => 'HyperText Markup Language',
                'points' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lesson_id' => 1, // HTML Basics
                'question' => 'Which HTML tag is used to create a hyperlink?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['<link>', '<a>', '<href>', '<url>']),
                'correct_answer' => '<a>',
                'points' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lesson_id' => 2, // CSS Styling
                'question' => 'CSS is used for styling web pages.',
                'question_type' => 'true_false',
                'options' => null,
                'correct_answer' => 'true',
                'points' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lesson_id' => 3, // JavaScript Fundamentals
                'question' => 'What is the correct way to declare a variable in JavaScript?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['var myVar;', 'variable myVar;', 'v myVar;', 'declare myVar;']),
                'correct_answer' => 'var myVar;',
                'points' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lesson_id' => 4, // Database Concepts
                'question' => 'Explain the concept of database normalization.',
                'question_type' => 'essay',
                'options' => null,
                'correct_answer' => 'Database normalization is the process of organizing data in a database to reduce redundancy and improve data integrity.',
                'points' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('quizzes')->insertBatch($data);
    }
}
