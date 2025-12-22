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

// Profile route
$routes->get('/profile', 'Profile::index');

// Course creation route
$routes->get('/create-courses', 'CourseCreator::create');


// Materials management routes
$routes->get('materials', 'Materials::index');
$routes->get('materials/dashboard', 'Student::materials');
$routes->get('materials/upload/(:num)', 'Materials::upload/$1');
$routes->post('materials/upload/(:num)', 'Materials::upload/$1');
$routes->get('materials/view/(:num)', 'Materials::view/$1');
$routes->get('materials/student/(:num)', 'Materials::studentMaterials/$1');
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');

// Admin course materials routes (alternative paths)
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');

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

// Course enrollment routes (require authentication)
$routes->post('/course/enroll', 'Course::enroll', ['filter' => 'auth']);
$routes->post('/course/unenroll', 'Course::unenroll', ['filter' => 'auth']);
$routes->get('/course/enrolled-courses', 'Course::getEnrolledCourses', ['filter' => 'auth']);

// Logout (requires authentication)
$routes->get('/logout', 'Auth::logout', ['filter' => 'auth']);

// Unified Dashboard (authentication handled in controller)
$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('/test-dashboard', 'Auth::testDashboard');
$routes->get('/test-login-redirect', 'TestLogin::index');
$routes->get('/quick-login', 'QuickLogin::index');

// Profile and Settings routes
$routes->get('/profile', 'Profile::index', ['filter' => 'auth']);
$routes->get('/settings', 'Settings::index', ['filter' => 'auth']);

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
    $routes->post('create-course', 'Admin::createCourse');
    $routes->get('get-course/(:num)', 'Admin::getCourse/$1');
    $routes->post('update-course', 'Admin::updateCourse');
    $routes->post('delete-course', 'Admin::deleteCourse');
    $routes->get('course-list', 'Admin::courseList');
    $routes->get('course-students/(:num)', 'Admin::courseStudents/$1');
    $routes->get('course-stats/(:num)', 'Admin::courseStats/$1');
    $routes->get('reports', 'Admin::reports');
    $routes->get('settings', 'Admin::settings');
    
    // Enrollment management routes
    $routes->get('enrollments', 'Admin::enrollments');
    $routes->get('enrollment-stats', 'Admin::enrollmentStats');
    $routes->get('enrollment-list', 'Admin::enrollmentList');
    $routes->get('students-list', 'Admin::studentsList');
    $routes->get('course-students', 'Admin::courseStudents');
    $routes->get('course-stats', 'Admin::courseStats');
    $routes->get('get-enrollment/(:num)', 'Admin::getEnrollment/$1');
    $routes->post('edit-enrollment', 'Admin::editEnrollment');
    $routes->post('bulk-enroll', 'Admin::bulkEnroll');
    $routes->get('export-enrollments', 'Admin::exportEnrollments');
    $routes->post('delete-enrollment', 'Admin::deleteEnrollment');
    
    // User management routes
    $routes->post('create-user', 'Admin::createUser');
    $routes->post('update-user', 'Admin::updateUser');
    $routes->post('delete-user', 'Admin::deleteUser');
});

// ============================================================================
// TEACHER ROUTES (Teacher role required)
// ============================================================================
$routes->group('teacher', ['filter' => 'roleauth:teacher'], function($routes){
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('courses', 'Teacher::courses');
    $routes->get('addCourse', 'Teacher::addCourse');
    $routes->post('addCourse', 'Teacher::addCourse');
    $routes->get('courses/create', 'Teacher::createCourse');
    $routes->post('courses/store', 'Teacher::storeCourse');
    $routes->get('assignments', 'Teacher::assignments');
    $routes->get('assignments/create', 'Teacher::createAssignment');
    $routes->get('createAssignment', 'Teacher::createAssignment');
    $routes->post('assignments/store', 'Teacher::storeAssignment');
    $routes->get('manageCourse/(:num)', 'Teacher::manageCourse/$1');
    $routes->get('students', 'Teacher::students');
    $routes->get('viewStudent/(:num)', 'Teacher::viewStudent/$1');
    $routes->get('emailStudent/(:num)', 'Teacher::emailStudent/$1');
    $routes->get('editStudent/(:num)', 'Teacher::editStudent/$1');
    $routes->post('sendEmail/(:num)', 'Teacher::sendEmail/$1');
    $routes->post('updateStudent/(:num)', 'Teacher::updateStudent/$1');
    $routes->get('grades', 'Teacher::grades');
    $routes->get('grade-book', 'Teacher::gradeBook');
});

// ============================================================================
// STUDENT ROUTES (Student role required)
// ============================================================================
$routes->group('student', ['filter' => 'roleauth:student'], function($routes){
    $routes->get('dashboard', 'Student::dashboard');
    $routes->get('courses', 'Student::courses');
    $routes->get('courses/enroll', 'Student::enrollCourse');
        $routes->get('assignments', 'Student::assignments');
    $routes->get('assignments/submit/(:num)', 'Student::submitAssignment/$1');
    $routes->post('assignments/submit/(:num)', 'Student::processSubmission/$1');
    $routes->get('grades', 'Student::grades');
});

// ============================================================================
// COURSE ROUTES (General course functionality)
// ============================================================================
$routes->get('/course', 'Course::index');
$routes->post('/course/unenroll', 'Course::unenroll');
$routes->get('/course/enrolled', 'Course::getEnrolledCourses');

// ============================================================================
// UTILITY ROUTES (Helper functions)
// ============================================================================
$routes->get('/quick-login', 'Auth::quickLogin');
$routes->post('/quick-login', 'Auth::quickLogin');
$routes->get('/enrollment-dashboard', 'Student::enrollmentDashboard');

// ============================================================================
// LEGACY ROUTES (Backward compatibility)
// ============================================================================
$routes->match(['GET','POST'], '/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout', ['filter' => 'auth']);

// Test session route (development only)
if (ENVIRONMENT !== 'production') {
    $routes->get('/test-session', 'TestSession::index');
    $routes->get('/debug', 'Debug::index');
    $routes->get('/debug/test-login', 'Debug::testLogin');
    $routes->get('/debug/clear', 'Debug::clearSession');
}
