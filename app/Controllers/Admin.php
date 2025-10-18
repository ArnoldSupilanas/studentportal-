<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'page_title' => 'Welcome, Admin!',
            'description' => 'Manage the entire system from this administrative dashboard.'
        ];
        
        return view('admin_dashboard', $data);
    }
}