<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCourseCodeToCourses extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'course_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'title'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'course_code');
    }
}
