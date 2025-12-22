<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    protected $announcementModel;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
    }

    public function index()
    {
        // Mock announcements data for now
        $announcements = [
            [
                'id' => 1,
                'title' => 'Welcome to the New LMS System',
                'content' => 'We are excited to launch our new Learning Management System! This platform will provide enhanced features for both students and instructors.',
                'created_at' => '2025-12-10 09:00:00'
            ],
            [
                'id' => 2,
                'title' => 'Final Exam Schedule Released',
                'content' => 'The final examination schedule for the Fall 2025 semester has been released. Please check your student portal for your specific exam dates and times.',
                'created_at' => '2025-12-08 14:30:00'
            ],
            [
                'id' => 3,
                'title' => 'System Maintenance Notice',
                'content' => 'The LMS system will undergo scheduled maintenance on December 15, 2025 from 2:00 AM to 6:00 AM. The system will be unavailable during this time.',
                'created_at' => '2025-12-05 11:15:00'
            ],
            [
                'id' => 4,
                'title' => 'New Course Registration Open',
                'content' => 'Registration for Spring 2026 courses is now open. Students can register through their student portal starting December 1, 2025.',
                'created_at' => '2025-12-01 08:00:00'
            ]
        ];
        
        $data = [
            'title' => 'Announcements',
            'announcements' => $announcements
        ];
        
        return view('announcements', $data);
    }
}