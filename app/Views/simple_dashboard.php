<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/index.php/dashboard">
                <i class="bi bi-speedometer2"></i> Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle"></i> <?= esc($name ?? 'Guest') ?>
                </span>
                <a class="nav-link" href="/index.php/logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">
                            <i class="bi bi-speedometer2"></i> 
                            <?php if ($role === 'admin'): ?>
                                Admin Dashboard
                            <?php elseif ($role === 'teacher'): ?>
                                Teacher Dashboard
                            <?php else: ?>
                                Student Dashboard
                            <?php endif; ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h4><i class="bi bi-check-circle"></i> Welcome to Your Dashboard!</h4>
                            <p class="mb-0">Hello <strong><?= esc($name) ?></strong>, you are logged in as a <strong><?= ucfirst(esc($role)) ?></strong>.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="text-primary"><?= $role_data['total_courses'] ?? 0 ?></h3>
                                        <p class="mb-0">Total Courses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="text-success"><?= $role_data['total_students'] ?? 0 ?></h3>
                                        <p class="mb-0">Total Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="text-warning"><?= $role_data['pending_assignments'] ?? 0 ?></h3>
                                        <p class="mb-0">Pending Tasks</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bi bi-person-badge"></i> Your Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Email:</strong> <?= esc($email) ?></p>
                                        <p><strong>User ID:</strong> <?= esc($user_id) ?></p>
                                        <p><strong>Role:</strong> <span class="badge bg-primary"><?= ucfirst(esc($role)) ?></span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bi bi-gear"></i> Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($role === 'admin'): ?>
                                            <a href="/index.php/admin/users" class="btn btn-primary mb-2 w-100">
                                                <i class="bi bi-people"></i> Manage Users
                                            </a>
                                            <a href="/index.php/admin/courses" class="btn btn-success mb-2 w-100">
                                                <i class="bi bi-book"></i> Manage Courses
                                            </a>
                                            <a href="/index.php/admin/reports" class="btn btn-info mb-2 w-100">
                                                <i class="bi bi-graph-up"></i> View Reports
                                            </a>
                                        <?php elseif ($role === 'teacher'): ?>
                                            <a href="/index.php/teacher/courses" class="btn btn-success mb-2 w-100">
                                                <i class="bi bi-book"></i> My Courses
                                            </a>
                                            <a href="/index.php/teacher/assignments" class="btn btn-info mb-2 w-100">
                                                <i class="bi bi-clipboard-check"></i> Assignments
                                            </a>
                                            <a href="/index.php/teacher/grades" class="btn btn-warning mb-2 w-100">
                                                <i class="bi bi-award"></i> Grade Students
                                            </a>
                                            <a href="/index.php/teacher/attendance" class="btn btn-secondary mb-2 w-100">
                                                <i class="bi bi-calendar-check"></i> Attendance
                                            </a>
                                        <?php else: ?>
                                            <a href="/index.php/student/courses" class="btn btn-success mb-2 w-100">
                                                <i class="bi bi-book"></i> My Courses
                                            </a>
                                            <a href="/index.php/student/assignments" class="btn btn-info mb-2 w-100">
                                                <i class="bi bi-journal-text"></i> Assignments
                                            </a>
                                            <a href="/index.php/student/grades" class="btn btn-warning mb-2 w-100">
                                                <i class="bi bi-award"></i> My Grades
                                            </a>
                                            <a href="/index.php/student/calendar" class="btn btn-secondary mb-2 w-100">
                                                <i class="bi bi-calendar3"></i> Academic Calendar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bi bi-clock-history"></i> Recent Activity</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group">
                                            <?php if ($role === 'admin'): ?>
                                                <div class="list-group-item">
                                                    <i class="bi bi-person-plus text-success"></i> New student registration: Alice Johnson
                                                    <small class="text-muted float-end">2 hours ago</small>
                                                </div>
                                                <div class="list-group-item">
                                                    <i class="bi bi-book-plus text-info"></i> New course created: Advanced Mathematics
                                                    <small class="text-muted float-end">5 hours ago</small>
                                                </div>
                                                <div class="list-group-item">
                                                    <i class="bi bi-graph-up text-warning"></i> System backup completed
                                                    <small class="text-muted float-end">1 day ago</small>
                                                </div>
                                            <?php elseif ($role === 'teacher'): ?>
                                                <div class="list-group-item">
                                                    <i class="bi bi-file-earmark-text text-success"></i> Assignment submitted: 15 students completed homework
                                                    <small class="text-muted float-end">1 hour ago</small>
                                                </div>
                                                <div class="list-group-item">
                                                    <i class="bi bi-calendar-check text-info"></i> Attendance marked for Computer Science class
                                                    <small class="text-muted float-end">3 hours ago</small>
                                                </div>
                                                <div class="list-group-item">
                                                    <i class="bi bi-chat-dots text-warning"></i> New message from student: Bob Smith
                                                    <small class="text-muted float-end">5 hours ago</small>
                                                </div>
                                            <?php else: ?>
                                                <div class="list-group-item">
                                                    <i class="bi bi-check-circle text-success"></i> Assignment submitted: Mathematics Homework #3
                                                    <small class="text-muted float-end">30 minutes ago</small>
                                                </div>
                                                <div class="list-group-item">
                                                    <i class="bi bi-trophy text-warning"></i> Grade received: A+ in English Essay
                                                    <small class="text-muted float-end">2 hours ago</small>
                                                </div>
                                                <div class="list-group-item">
                                                    <i class="bi bi-calendar-event text-info"></i> Upcoming deadline: Science Project - Tomorrow
                                                    <small class="text-muted float-end">1 day ago</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
