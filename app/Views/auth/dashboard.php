<?php 
helper('text');

// Include header template for CSS and proper HTML structure
$headerData = ['title' => 'Student Dashboard'];
if (isset($data)) {
    $headerData = $headerData + $data;
}
echo view('templates/header', $headerData);

// Debug: Check if role_data exists and has content
if (!isset($role_data) || empty($role_data)) {
    $role_data = [
        'total_users' => 0,
        'total_students' => 0, 
        'total_teachers' => 0,
        'total_admins' => 0,
        'recent_users' => []
    ];
}
?>

    <!-- Navigation is now in header template -->

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <h1 class="display-4">
                <i class="bi bi-speedometer2"></i> 
                <?php if ($role === 'admin'): ?>
                    Admin Dashboard
                <?php elseif ($role === 'teacher'): ?>
                    Teacher Dashboard
                <?php else: ?>
                    Student Dashboard
                <?php endif; ?>
            </h1>
            <p class="lead mb-0">Welcome back, <?= esc($name) ?>!</p>
            <span class="badge bg-light text-dark mt-2">
                <i class="bi bi-shield-check"></i> Role: <?= ucfirst(esc($role)) ?>
            </span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- User Information Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="info-card">
                    <h3 class="mb-4">
                        <i class="bi bi-person-badge"></i> Your Profile Information
                    </h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name</label>
                            <h5><i class="bi bi-person"></i> <?= esc($name) ?></h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email Address</label>
                            <h5><i class="bi bi-envelope"></i> <?= esc($email) ?></h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Role</label>
                            <h5>
                                <span class="badge bg-primary badge-role">
                                    <i class="bi bi-shield-check"></i> <?= ucfirst(esc($role)) ?>
                                </span>
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <h5>
                                <span class="badge bg-success badge-role">
                                    <i class="bi bi-check-circle"></i> Active
                                </span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role-Based Dashboard Cards -->
        <?php if ($role === 'admin'): ?>
            <!-- Admin Dashboard Content -->
            <div class="row mb-4">
                <div class="col-md-3 mb-4">
                    <div class="card text-center p-4 bg-primary text-white">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3><?= $role_data['total_users'] ?? 0 ?></h3>
                            <p class="mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center p-4 bg-success text-white">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <h3><?= $role_data['total_students'] ?? 0 ?></h3>
                            <p class="mb-0">Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center p-4 bg-info text-white">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="bi bi-person-workspace"></i>
                            </div>
                            <h3><?= $role_data['total_teachers'] ?? 0 ?></h3>
                            <p class="mb-0">Teachers</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center p-4 bg-warning text-white">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <h3><?= $role_data['total_admins'] ?? 0 ?></h3>
                            <p class="mb-0">Admins</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Action Cards -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-primary">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h5 class="card-title">Manage Users</h5>
                            <p class="card-text text-muted">Add, edit, or remove users</p>
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-right"></i> Manage
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-success">
                                <i class="bi bi-book-fill"></i>
                            </div>
                            <h5 class="card-title">Manage Courses</h5>
                            <p class="card-text text-muted">Create and manage courses</p>
                            <a href="<?= base_url('admin/courses') ?>" class="btn btn-outline-success">
                                <i class="bi bi-arrow-right"></i> Manage
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-warning">
                                <i class="bi bi-bar-chart-fill"></i>
                            </div>
                            <h5 class="card-title">View Reports</h5>
                            <p class="card-text text-muted">System analytics and reports</p>
                            <a href="<?= base_url('admin/reports') ?>" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-right"></i> View
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-info">
                                <i class="bi bi-folder-fill"></i>
                            </div>
                            <h5 class="card-title">Manage Materials</h5>
                            <p class="card-text text-muted">Upload and manage course materials</p>
                            <a href="<?= base_url('materials') ?>" class="btn btn-outline-info">
                                <i class="bi bi-arrow-right"></i> Manage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Users Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Users</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($role_data['recent_users']) && !empty($role_data['recent_users'])): ?>
                                            <?php foreach ($role_data['recent_users'] as $user): ?>
                                                <tr>
                                                    <td><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                                    <td><?= esc($user['email']) ?></td>
                                                    <td><span class="badge bg-info"><?= ucfirst(esc($user['role'])) ?></span></td>
                                                    <td><span class="badge bg-success"><?= ucfirst(esc($user['status'])) ?></span></td>
                                                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No users found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php elseif ($role === 'teacher'): ?>
            <!-- Teacher Dashboard Content -->
            <div class="row mb-4">
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4 bg-info text-white">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3><?= $role_data['total_students'] ?? 0 ?></h3>
                            <p class="mb-0">Total Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4 bg-success text-white">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="bi bi-book-fill"></i>
                            </div>
                            <h3>0</h3>
                            <p class="mb-0">My Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4 bg-warning text-white">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <h3>0</h3>
                            <p class="mb-0">Assignments</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Teacher Action Cards -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-primary">
                                <i class="bi bi-book"></i>
                            </div>
                            <h5 class="card-title">My Courses</h5>
                            <p class="card-text text-muted">Manage your courses</p>
                            <a href="<?= base_url('student/courses') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-right"></i> View Courses
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-success">
                                <i class="bi bi-clipboard-plus"></i>
                            </div>
                            <h5 class="card-title">Create Assignment</h5>
                            <p class="card-text text-muted">Create new assignments</p>
                            <a href="#" class="btn btn-outline-success">
                                <i class="bi bi-arrow-right"></i> Create
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-warning">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="card-title">View Students</h5>
                            <p class="card-text text-muted">Manage student grades</p>
                            <a href="#" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-right"></i> View Students
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-info">
                                <i class="bi bi-folder"></i>
                            </div>
                            <h5 class="card-title">Manage Materials</h5>
                            <p class="card-text text-muted">Upload and manage course materials</p>
                            <a href="<?= base_url('materials') ?>" class="btn btn-outline-info">
                                <i class="bi bi-arrow-right"></i> Manage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Student Dashboard Content -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-primary">
                                <i class="bi bi-book"></i>
                            </div>
                            <h5 class="card-title">My Courses</h5>
                            <p class="card-text text-muted">View and manage your enrolled courses</p>
                            <a href="<?= base_url('student/courses') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-right"></i> View Courses
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-success">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <h5 class="card-title">Assignments</h5>
                            <p class="card-text text-muted">Check your pending assignments</p>
                            <a href="#" class="btn btn-outline-success">
                                <i class="bi bi-arrow-right"></i> View Assignments
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-warning">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <h5 class="card-title">Grades</h5>
                            <p class="card-text text-muted">View your academic performance</p>
                            <a href="#" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-right"></i> View Grades
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <div class="card-body">
                            <div class="card-icon text-info">
                                <i class="bi bi-folder"></i>
                            </div>
                            <h5 class="card-title">Course Materials</h5>
                            <p class="card-text text-muted">Download course materials and resources</p>
                            <a href="<?= base_url('materials/dashboard') ?>" class="btn btn-outline-info">
                                <i class="bi bi-arrow-right"></i> View Materials
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Courses Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-book-fill me-2"></i>
                                Enrolled Courses
                            </h5>
                        </div>
                        <div class="card-body enrolled-courses-container">
                            <?php if (isset($enrolled_courses) && !empty($enrolled_courses)): ?>
                                <div class="row" id="enrolledCoursesList">
                                    <?php foreach ($enrolled_courses as $course): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary">
                                                        <i class="bi bi-book me-2"></i>
                                                        <?= esc($course['title']) ?>
                                                    </h6>
                                                    <p class="card-text text-muted small">
                                                        <?= character_limiter(esc($course['description']), 80) ?>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar me-1"></i>
                                                            Enrolled: <?= date('M d, Y', strtotime($course['enrollment_date'])) ?>
                                                        </small>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="unenrollFromCourse(<?= $course['course_id'] ?>)">
                                                            <i class="bi bi-x-circle me-1"></i>
                                                            Unenroll
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">You are not enrolled in any courses yet.</p>
                                    <p class="text-muted">Browse the available courses below to get started!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Courses Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Available Courses
                                        <span class="badge bg-primary ms-2" id="course-count"><?php echo isset($available_courses) ? count($available_courses) : 0; ?></span>
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <!-- Search Box -->
                                        <div class="input-group input-group-sm" style="max-width: 200px;">
                                            <input type="text" class="form-control" id="courseSearch" placeholder="Search courses...">
                                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Sort Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                                                <i class="bi bi-sort-down"></i> Sort
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" data-sort="title-asc">
                                                    <i class="bi bi-sort-alpha-down"></i> Title (A-Z)
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="title-desc">
                                                    <i class="bi bi-sort-alpha-up"></i> Title (Z-A)
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="newest">
                                                    <i class="bi bi-clock"></i> Newest First
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="instructor">
                                                    <i class="bi bi-person"></i> By Instructor
                                                </a></li>
                                            </ul>
                                        </div>
                                        
                                        <!-- View Toggle -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <input type="radio" class="btn-check" name="viewMode" id="gridView" autocomplete="off" checked>
                                            <label class="btn btn-outline-secondary" for="gridView">
                                                <i class="bi bi-grid-3x3-gap"></i>
                                            </label>
                                            <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off">
                                            <label class="btn btn-outline-secondary" for="listView">
                                                <i class="bi bi-list-ul"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($available_courses) && count($available_courses) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Course Name</th>
                                                <th>Code</th>
                                                <th>Teacher</th>
                                                <th>Students</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $enrolledCourseIds = [];
                                                if (!empty($enrolled_courses)) {
                                                    foreach ($enrolled_courses as $ec) { $enrolledCourseIds[] = (int)$ec['course_id']; }
                                                }
                                            ?>
                                            <?php foreach ($available_courses as $i => $course): ?>
                                                <?php 
                                                    $code = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $course['title']), 0, 6));
                                                    $students = (int)($course['students_count'] ?? 0);
                                                    $teacher = $course['instructor_name'] ?? 'TBA';
                                                ?>
                                                <tr>
                                                    <td><?= esc($i + 1) ?></td>
                                                    <td><?= esc($course['title']) ?></td>
                                                    <td class="text-uppercase text-primary fw-semibold"><?= esc($code) ?></td>
                                                    <td><?= esc($teacher) ?></td>
                                                    <td><span class="badge bg-info"><?= $students ?> Students</span></td>
                                                    <td>
                                                        <?php $already = in_array((int)$course['id'], $enrolledCourseIds, true); ?>
                                                        <form method="post" action="<?= base_url('course/enroll') ?>" class="d-inline enroll-form" id="enroll-form-<?= $course['id'] ?>">
                                                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                                            <button type="button" class="btn btn-sm <?= $already ? 'btn-secondary' : 'btn-primary' ?> enroll-btn <?= $already ? '' : '' ?>"
                                                                    data-course-id="<?= $course['id'] ?>"
                                                                    data-course-title="<?= esc($course['title']) ?>"
                                                                    onclick="return enrollInCourse(<?= $course['id'] ?>)"
                                                                    <?= $already ? 'disabled' : '' ?>>
                                                                <i class="bi bi-plus-circle me-1"></i> <?= $already ? 'Enrolled' : 'Enroll' ?>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2 unenroll-btn <?= $already ? '' : 'd-none' ?>"
                                                                data-course-id="<?= $course['id'] ?>"
                                                                data-course-title="<?= esc($course['title']) ?>"
                                                                onclick="unenrollFromCourse(<?= $course['id'] ?>)">
                                                            <i class="bi bi-x-circle me-1"></i> Unenroll
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <p class="text-muted">No courses are currently available for enrollment.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Additional Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading">
                        <i class="bi bi-info-circle"></i> Role-Based Access Control
                    </h5>
                    <p class="mb-0">
                        You are logged in as <strong><?= esc($name) ?></strong> with the role of <strong><?= ucfirst(esc($role)) ?></strong>.
                        Your dashboard displays content and features specific to your role.
                    </p>
                </div>
            </div>
        </div>
    </div>

 

<!-- Alert Container for Dynamic Messages -->
<div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>

<!-- Enrollment Confirmation Modal -->
<div class="modal fade" id="enrollConfirmModal" tabindex="-1" aria-labelledby="enrollConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollConfirmModalLabel">Confirm Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to enroll in this course?</p>
                <p class="text-muted">You will be enrolled and can access course materials immediately.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmEnrollBtn">Confirm Enrollment</button>
            </div>
        </div>
    </div>
</div>

<!-- Unenrollment Confirmation Modal -->
<div class="modal fade" id="unenrollConfirmModal" tabindex="-1" aria-labelledby="unenrollConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unenrollConfirmModalLabel">Confirm Unenrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unenroll from this course?</p>
                <p class="text-muted">You will lose access to course materials and progress.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmUnenrollBtn">Confirm Unenrollment</button>
            </div>
        </div>
    </div>
</div>

<script>
    console.log("Dashboard loaded with WORKING enrollment system");

    var csrfTokenName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';
    var csrfCookieName = '<?= config('Security')->cookieName ?>';
    var csrfHeaderName = '<?= config('Security')->headerName ?>';
    function getCookie(name){
        var match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()\\\/\+\^])/g, '\\$1') + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : '';
    }

    // Enrollment function (direct, without modal to ensure functionality)
    function enrollInCourse(courseId) {
        var $btn = $(".enroll-btn[data-course-id='" + courseId + "']");
        var courseTitle = $btn.data("course-title") || "this course";
        

        // Show processing state
        $btn.prop("disabled", true).html('<i class="bi bi-hourglass-split"></i> Processing...');

        var handleSuccess = function(data){
            if (data && data.success) {
                // Update CSRF token if provided
                if (data.csrf_hash) {
                    csrfHash = data.csrf_hash;
                    // Update cookie if needed
                    if (csrfCookieName) {
                        document.cookie = csrfCookieName + "=" + data.csrf_hash + "; path=/";
                    }
                }
                
                showAlert("success", data.message || ("Successfully enrolled in " + courseTitle));
                $btn
                    .prop("disabled", true)
                    .removeClass("btn-primary")
                    .addClass("btn-secondary")
                    .html('<i class="bi bi-check-circle"></i> Enrolled')
                    .attr("onclick", "");

                // Increment students badge in the row
                var $badge = $btn.closest('tr').find('td:nth-child(5) .badge');
                if ($badge.length) {
                    var m = ($badge.text().match(/(\d+)/) || [0, '0'])[1];
                    var updated = parseInt(m, 10) + 1;
                    $badge.text(updated + ' Students');
                }
                // Show Unenroll button in the row
                var $unenBtn = $btn.closest('tr').find('.unenroll-btn');
                if ($unenBtn.length) { $unenBtn.removeClass('d-none').prop('disabled', false); }

                if (data.course_data && typeof addCourseToEnrolledNew === 'function') {
                    addCourseToEnrolledNew(data.course_data);
                }
            } else {
                showAlert("danger", (data && data.message) || "Enrollment failed");
                $btn.prop("disabled", false).html('<i class="bi bi-plus-circle"></i> Enroll');
            }
        };
        var handleError = function(){
            showAlert("danger", "An error occurred while enrolling. Please try again.");
            $btn.prop("disabled", false).html('<i class="bi bi-plus-circle"></i> Enroll');
        };

        var url = "<?= base_url('course/enroll') ?>";
        // Refresh CSRF from cookie just before sending (in case it rotated)
        var latest = getCookie(csrfCookieName); if (latest) { csrfHash = latest; }
        // Prefer fetch; fallback to jQuery Ajax; final fallback to normal form POST
        if (window.fetch) {
            var form = new URLSearchParams();
            form.append('course_id', courseId);
            form.append(csrfTokenName, csrfHash);
            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: form.toString()
            }).then(function(res){
                var t = (csrfHeaderName && res.headers) ? res.headers.get(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                return res.json().catch(function(){ return {}; });
            })
              .then(handleSuccess)
              .catch(handleError);
        } else if (window.$ && $.ajax) {
            var latest = getCookie(csrfCookieName); if (latest) { csrfHash = latest; }
        var postData = { course_id: courseId };
            postData[csrfTokenName] = csrfHash;
            $.ajax({ url: url, method: 'POST', data: postData, dataType: 'json' })
             .done(function(data, textStatus, jqXHR){
                var t = jqXHR && jqXHR.getResponseHeader ? jqXHR.getResponseHeader(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                handleSuccess(data);
             })
             .fail(function(jqXHR){
                var t = jqXHR && jqXHR.getResponseHeader ? jqXHR.getResponseHeader(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                handleError();
             });
        } else {
            enrollFallbackPost(courseId);
        }
        return false;
    }
    // Ensure function is available globally for inline onclick handlers
    window.enrollInCourse = enrollInCourse;

    function enrollFallbackPost(courseId) {
        var url = "<?= base_url('course/enroll') ?>";
        var f = document.getElementById('enrollFallbackForm');
        if (!f) {
            f = document.createElement('form');
            f.method = 'POST';
            f.action = url;
            f.id = 'enrollFallbackForm';
            var inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'course_id';
            f.appendChild(inp);
            document.body.appendChild(f);
        }
        f.querySelector('input[name="course_id"]').value = courseId;
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = csrfTokenName;
        hidden.value = csrfHash || getCookie(csrfCookieName) || '';
        f.appendChild(hidden);
        f.submit();
    }

    // Unenroll function
    function unenrollFromCourse(courseId) {
        var $unenBtn = $(".unenroll-btn[data-course-id='" + courseId + "']");
        var $row = $unenBtn.closest('tr');
        var $enrollBtn = $(".enroll-btn[data-course-id='" + courseId + "']");
        var courseTitle = ($unenBtn.data("course-title") || $enrollBtn.data("course-title") || "this course");
        if (!confirm("Unenroll from " + courseTitle + "?")) return;

        // Disable buttons during processing
        $unenBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Processing...');
        $enrollBtn.prop('disabled', true);

        var handleSuccess = function(data){
            if (data && data.success) {
                // Update CSRF token if provided
                if (data.csrf_hash) {
                    csrfHash = data.csrf_hash;
                    // Update cookie if needed
                    if (csrfCookieName) {
                        document.cookie = csrfCookieName + "=" + data.csrf_hash + "; path=/";
                    }
                }
                
                showAlert('success', data.message || ('Successfully unenrolled from ' + courseTitle));

                // Reset Enroll button
                $enrollBtn
                    .prop('disabled', false)
                    .removeClass('btn-secondary')
                    .addClass('btn-primary')
                    .html('<i class="bi bi-plus-circle"></i> Enroll');

                // Hide Unenroll button
                $unenBtn.addClass('d-none').prop('disabled', false).html('<i class="bi bi-x-circle me-1"></i> Unenroll');

                // Decrement students badge
                var $badge = $row.find('td:nth-child(5) .badge');
                if ($badge.length) {
                    var m = ($badge.text().match(/(\d+)/) || [0, '0'])[1];
                    var updated = Math.max(0, parseInt(m, 10) - 1);
                    $badge.text(updated + ' Students');
                }

                // Attempt to remove from enrolled courses list (if present)
                var $card = $("#enrolledCoursesList .col-md-4.mb-3").filter(function(){
                    return $(this).find('.card-title').text().trim() === ($enrollBtn.data('course-title') || '');
                }).first();
                if ($card.length) { $card.remove(); }
            } else {
                showAlert('danger', (data && data.message) || 'Unenrollment failed');
                $unenBtn.prop('disabled', false).html('<i class="bi bi-x-circle me-1"></i> Unenroll');
                $enrollBtn.prop('disabled', false);
            }
        };
        var handleError = function(){
            showAlert('danger', 'An error occurred while unenrolling. Please try again.');
            $unenBtn.prop('disabled', false).html('<i class="bi bi-x-circle me-1"></i> Unenroll');
            $enrollBtn.prop('disabled', false);
        };

        var url = "<?= base_url('course/unenroll') ?>";
        var latest = getCookie(csrfCookieName); if (latest) { csrfHash = latest; }
        if (window.fetch) {
            var form = new URLSearchParams();
            form.append('course_id', courseId);
            form.append(csrfTokenName, csrfHash);
            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: form.toString()
            }).then(function(res){
                var t = (csrfHeaderName && res.headers) ? res.headers.get(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                return res.json().catch(function(){ return {}; });
            })
              .then(handleSuccess)
              .catch(handleError);
        } else if (window.$ && $.ajax) {
            var postData = { course_id: courseId };
            postData[csrfTokenName] = csrfHash;
            $.ajax({ url: url, method: 'POST', data: postData, dataType: 'json' })
             .done(function(data, textStatus, jqXHR){
                var t = jqXHR && jqXHR.getResponseHeader ? jqXHR.getResponseHeader(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                handleSuccess(data);
             })
             .fail(function(jqXHR){
                var t = jqXHR && jqXHR.getResponseHeader ? jqXHR.getResponseHeader(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                handleError();
             });
        } else {
            handleError();
        }
    }
    window.unenrollFromCourse = unenrollFromCourse;

    // Handle enrollment confirmation
    $(document).on("click", "#confirmEnrollBtn", function() {
        var courseId = $(this).data("course-id");
        var courseTitle = $(this).data("course-title");
        
        console.log("CONFIRMING ENROLLMENT - Course:", courseTitle, "ID:", courseId);
        
        // Disable button during processing
        $(this).prop("disabled", true).html('<i class="bi bi-hourglass-split"></i> Processing...');
        
        var postData = { course_id: courseId };
        postData[csrfTokenName] = csrfHash;
        $.ajax({
            url: "<?= base_url('course/enroll') ?>",
            method: "POST",
            data: postData,
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                var t = jqXHR && jqXHR.getResponseHeader ? jqXHR.getResponseHeader(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                console.log("ENROLLMENT RESPONSE:", data);
                
                if (data.success) {
                    // Show success message
                    showAlert("success", data.message || "Successfully enrolled in " + courseTitle);
                    
                    // Disable enroll button and change to enrolled state
                    $(".enroll-btn[data-course-id='" + courseId + "']")
                        .prop("disabled", true)
                        .removeClass("btn-primary")
                        .addClass("btn-secondary")
                        .html('<i class="bi bi-check-circle"></i> Enrolled')
                        .attr("onclick", "");
                    
                    var $btn = $(".enroll-btn[data-course-id='" + courseId + "']");
                    var $badge = $btn.closest('tr').find('td:nth-child(5) .badge');
                    if ($badge.length) {
                        var m = ($badge.text().match(/(\d+)/) || [0, '0'])[1];
                        var updated = parseInt(m, 10) + 1;
                        $badge.text(updated + ' Students');
                    }
                    
                    if (data.course_data && typeof addCourseToEnrolledNew === 'function') {
                        addCourseToEnrolledNew(data.course_data);
                    }
                    
                    // Hide modal (Bootstrap 5 API)
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('enrollConfirmModal')).hide();
                } else {
                    // Show error message
                    showAlert("danger", data.message || "Enrollment failed");
                    
                    // Re-enable button
                    $(this).prop("disabled", false).html('<i class="bi bi-plus-circle"></i> Enroll');
                }
            },
            error: function(xhr, status, error) {
                var t = xhr && xhr.getResponseHeader ? xhr.getResponseHeader(csrfHeaderName) : null;
                if (t) { csrfHash = t; }
                console.error("ENROLLMENT ERROR:", status, error);
                showAlert("danger", "An error occurred while enrolling. Please try again.");
                
                // Re-enable button
                $(this).prop("disabled", false).html('<i class="bi bi-plus-circle"></i> Enroll');
            }
        });
    });

    // Bind click on Enroll buttons (delegated)
    $(document).on('click', '.enroll-btn', function(e){
        e.preventDefault();
        var $btn = $(this);
        if ($btn.prop('disabled')) return;
        var cid = $btn.data('course-id');
        if (cid) { enrollInCourse(cid); }
    });
    // Intercept form submission as a reliable fallback
    $(document).on('submit', '.enroll-form', function(e){
        e.preventDefault();
        var cid = $(this).find('input[name="course_id"]').val();
        if (cid) { enrollInCourse(cid); }
    });

    // Bind click on Unenroll buttons
    $(document).on('click', '.unenroll-btn', function(e){
        e.preventDefault();
        var cid = $(this).data('course-id');
        if (cid) { unenrollFromCourse(cid); }
    });
    

    function addCourseToEnrolledNew(courseData) {
        console.log("Adding course to enrolled section:", courseData);
        if (!document.getElementById('enrolledCoursesList')) {
            var container = $('<div class="row" id="enrolledCoursesList"></div>');
            $('.enrolled-courses-container').html(container);
        }
        var enrolledDate = new Date();
        var dateStr = enrolledDate.toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' });
        var courseHtml = '' +
            '<div class="col-md-4 mb-3" id="enrolled-card-' + (courseData.course_id || 0) + '">' +
                '<div class="card h-100">' +
                    '<div class="card-body">' +
                        '<h6 class="card-title text-primary">' +
                            '<i class="bi bi-book me-2"></i>' +
                            (courseData.title || 'Course') +
                        '</h6>' +
                        '<p class="card-text text-muted small">' +
                            (courseData.description || 'You are enrolled in this course.') +
                        '</p>' +
                        '<div class="d-flex justify-content-between align-items-center">' +
                            '<small class="text-muted">' +
                                '<i class="bi bi-calendar me-1"></i>' +
                                'Enrolled: ' + dateStr +
                            '</small>' +
                            '<button class="btn btn-sm btn-outline-danger" onclick="typeof unenrollFromCourse === \"function\" && unenrollFromCourse(' + (courseData.course_id || 0) + ')">' +
                                '<i class="bi bi-x-circle me-1"></i> Unenroll' +
                            '</button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        $("#enrolledCoursesList").prepend(courseHtml);
    }

    // Simple alert helper if not defined elsewhere
    if (typeof window.showAlert !== 'function') {
        window.showAlert = function(type, message) {
            var container = document.getElementById('alert-container');
            if (!container) return alert(message);
            var cls = type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : 'alert-info');
            var el = document.createElement('div');
            el.className = 'alert ' + cls + ' alert-dismissible fade show';
            el.role = 'alert';
            el.innerHTML = '<i class="bi bi-info-circle"></i> ' + message + '\n                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            container.appendChild(el);
            if (window.bootstrap && bootstrap.Alert) {
                setTimeout(function(){ var m = bootstrap.Alert.getOrCreateInstance(el); m.close(); }, 4000);
            }
        };
    }
 
</script>

<?php echo view('templates/footer'); ?>
