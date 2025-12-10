<?php 
// Include header template
$headerData = [
    'title' => 'Dashboard - Student Portal',
    'role' => $role ?? 'student',
    'is_logged_in' => $is_logged_in ?? false,
    'name' => $name ?? 'Guest'
];
echo view('templates/header', $headerData);
?>

<body>
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
                            <h3>1</h3>
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
                            <a href="#" class="btn btn-outline-primary">
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
                            <a href="#" class="btn btn-outline-success">
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
                            <a href="#" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-right"></i> View
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
                            <a href="#" class="btn btn-outline-primary">
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
                                <i class="bi bi-arrow-right"></i> View
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
                            <a href="#" class="btn btn-outline-primary">
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

<?php echo view('templates/footer'); ?>
