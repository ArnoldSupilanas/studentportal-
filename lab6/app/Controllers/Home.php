<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to LMS',
            'page_title' => 'Learning Management System',
            'description' => 'Your comprehensive learning platform for web development, database design, and programming courses.'
        ];
        
        return view('index', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => 'About LMS',
            'page_title' => 'About Our Learning Management System',
            'description' => 'Learn more about our comprehensive learning platform designed for students and instructors.'
        ];
        
        return view('about', $data);
    }
    
    public function contact()
    {
        $data = [
            'title' => 'Contact Us',
            'page_title' => 'Get in Touch',
            'description' => 'Have questions? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.'
        ];
        
        return view('contact', $data);
    }
}