<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// ============================================================================
// PUBLIC ROUTES (No authentication required)
// ============================================================================

// Home/Landing pages
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// Public announcements
$routes->get('/announcements', 'Announcement::index');

// Authentication routes (login/register)
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');

// Additional auth routes for compatibility
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register', 'Auth::register');

// ============================================================================
// PROTECTED ROUTES (Authentication required)
// ============================================================================

// Logout (requires authentication)
$routes->get('/logout', 'Auth::logout', ['filter' => 'auth']);

// Unified Dashboard (authentication handled in controller)
$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('/test-dashboard', 'Auth::testDashboard');
$routes->get('/test-login-redirect', 'TestLogin::index');
$routes->get('/quick-login', 'QuickLogin::index');

// ============================================================================
// ADMIN ROUTES (Admin role required)
// ============================================================================
$routes->group('admin', ['filter' => 'roleauth:admin'], function($routes){
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'Admin::users');
    $routes->get('users/create', 'Admin::createUser');
    $routes->post('users/store', 'Admin::storeUser');
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
    $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');
    $routes->get('courses', 'Admin::courses');
    $routes->get('reports', 'Admin::reports');
    $routes->get('settings', 'Admin::settings');
});

// ============================================================================
// TEACHER ROUTES (Teacher role required)
// ============================================================================
$routes->group('teacher', ['filter' => 'roleauth:teacher'], function($routes){
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('courses', 'Teacher::courses');
    $routes->get('courses/create', 'Teacher::createCourse');
    $routes->post('courses/store', 'Teacher::storeCourse');
    $routes->get('assignments', 'Teacher::assignments');
    $routes->get('assignments/create', 'Teacher::createAssignment');
    $routes->post('assignments/store', 'Teacher::storeAssignment');
    $routes->get('students', 'Teacher::students');
    $routes->get('grades', 'Teacher::grades');
});

// ============================================================================
// STUDENT ROUTES (Student role required)
// ============================================================================
$routes->group('student', ['filter' => 'roleauth:student'], function($routes){
    $routes->get('dashboard', 'Student::dashboard');
    $routes->get('courses', 'Student::courses');
    $routes->get('courses/enroll', 'Student::enrollCourse');
    $routes->post('courses/enroll', 'Student::processEnrollment');
    $routes->get('assignments', 'Student::assignments');
    $routes->get('assignments/submit/(:num)', 'Student::submitAssignment/$1');
    $routes->post('assignments/submit/(:num)', 'Student::processSubmission/$1');
    $routes->get('grades', 'Student::grades');
});

// ============================================================================
// COURSE ROUTES (General course functionality)
// ============================================================================
$routes->post('/course/enroll', 'Course::enroll');
$routes->post('/course/unenroll', 'Course::unenroll');
$routes->get('/course/enrolled-courses', 'Course::getEnrolledCourses');
$routes->get('/course', 'Course::index');

// ============================================================================
// LEGACY ROUTES (Backward compatibility)
// ============================================================================
$routes->match(['get','post'], '/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout', ['filter' => 'auth']);

// Test session route (development only)
if (ENVIRONMENT !== 'production') {
    $routes->get('/test-session', 'TestSession::index');
    $routes->get('/debug', 'Debug::index');
    $routes->get('/debug/test-login', 'Debug::testLogin');
    $routes->get('/debug/clear', 'Debug::clearSession');
}
