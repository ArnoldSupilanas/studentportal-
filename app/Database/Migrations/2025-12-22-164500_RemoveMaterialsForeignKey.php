<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveMaterialsForeignKey extends Migration
{
    public function up()
    {
        // Drop the foreign key constraint
        $this->forge->dropForeignKey('course_id', 'materials');
    }

    public function down()
    {
        // Re-add the foreign key constraint
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
    }
}
