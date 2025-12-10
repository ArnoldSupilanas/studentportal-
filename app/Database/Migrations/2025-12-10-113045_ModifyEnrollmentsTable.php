<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyEnrollmentsTable extends Migration
{
    public function up()
    {
        // Rename student_id to user_id
        $this->forge->modifyColumn('enrollments', [
            'student_id' => [
                'name' => 'user_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        
        // Add foreign key constraints
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        // Drop foreign keys first
        $this->forge->dropForeignKey('enrollments', 'enrollments_user_id_foreign');
        $this->forge->dropForeignKey('enrollments', 'enrollments_course_id_foreign');
        
        // Rename user_id back to student_id
        $this->forge->modifyColumn('enrollments', [
            'user_id' => [
                'name' => 'student_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
    }
}
