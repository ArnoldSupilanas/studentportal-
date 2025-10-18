<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Custom routes for Home controller
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// Announcements route
$routes->get('/announcements', 'Announcement::index');

// Auth routes
$routes->match(['get','post'], '/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');

// Teacher routes (protected)
$routes->group('teacher', ['filter' => 'roleauth'], function($routes){
    $routes->get('dashboard', 'Teacher::dashboard');
});

// Admin routes (protected)
$routes->group('admin', ['filter' => 'roleauth'], function($routes){
    $routes->get('dashboard', 'Admin::dashboard');
});
