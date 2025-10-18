<?php

namespace App\Controllers;

class Teacher extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Teacher Dashboard',
            'page_title' => 'Welcome, Teacher!',
            'description' => 'Manage your courses and students from this dashboard.'
        ];
        
        return view('teacher_dashboard', $data);
    }
}