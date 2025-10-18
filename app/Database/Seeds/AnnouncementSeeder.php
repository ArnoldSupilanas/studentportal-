<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the New Academic Year',
                'content' => 'We are excited to welcome all students to the new academic year. Please make sure to check your course schedules and attend all orientation sessions. If you have any questions, feel free to contact the student services office.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Library Hours Update',
                'content' => 'The university library will now be open 24/7 during exam periods. Please note that quiet study areas are available on the 3rd and 4th floors. Group study rooms can be reserved through the online booking system.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'title' => 'Important: Course Registration Deadline',
                'content' => 'The deadline for course registration is approaching. Please ensure you have registered for all required courses by the end of this week. Late registrations will incur additional fees.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
        ];

        $this->db->table('announcements')->insertBatch($data);
    }
}
