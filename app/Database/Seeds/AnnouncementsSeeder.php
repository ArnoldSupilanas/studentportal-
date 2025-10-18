<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the new semester',
                'content' => 'Classes begin on August 15. Please check your schedules and enrollments.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
            ],
            [
                'title' => 'Portal Maintenance',
                'content' => 'The portal will be under maintenance this Saturday from 1AM to 4AM.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
        ];

        $this->db->table('announcements')->insertBatch($data);
    }
}
