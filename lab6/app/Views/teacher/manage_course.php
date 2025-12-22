<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Student Portal</title>
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
                <a class="nav-link" href="/index.php/dashboard">
                    <i class="bi bi-house"></i> Dashboard
                </a>
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
                    <div class="card-header bg-success text-white">
                        <h2 class="mb-0">
                            <i class="bi bi-book"></i> <?= esc($page_title) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted"><?= esc($description) ?></p>
                        
                        <!-- Course Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="bi bi-book"></i> <?= esc($course['name']) ?>
                                        </h5>
                                        <p class="card-text">
                                            <i class="bi bi-people"></i> <?= $course['students'] ?> Students<br>
                                            <i class="bi bi-graph-up"></i> <?= $course['progress'] ?>% Complete
                                        </p>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" style="width: <?= $course['progress'] ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Quick Actions</h5>
                                        <div class="d-grid gap-2">
                                            <a href="<?= base_url('teacher/createAssignment') ?>" class="btn btn-primary">
                                                <i class="bi bi-plus-circle"></i> Create Assignment
                                            </a>
                                            <a href="<?= base_url('teacher/assignments') ?>" class="btn btn-info">
                                                <i class="bi bi-clipboard-check"></i> View All Assignments
                                            </a>
                                            <a href="<?= base_url('teacher/courses') ?>" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Back to Courses
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Students Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="bi bi-people"></i> Enrolled Students
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <﻿﻿<th‏th> sunlight="email">Email</  </themptied>
                                                        < {th> 
                                                        <th>Grade</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($students as $student): ?>
                                                    <tr>
                                                        <td><?= esc($student['name']) ?></td>
                                                        <td><?= esc($student['email']) ?></td>
                                                        <td>
                                                            <span class="badge bg-success"><?= esc($student['grade']) ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary">
                                                                <i class="bi bi-eye"></i> View
                                                            </button>
                                                            <button class="btn btn-sm btn-warning">
                                                                <i class="bi bi-pencil"></i> Grade
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Assignments Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">
                                            <i class="bi bi-clipboard-check"></i> Recent Assignments
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Assignment</th>
                                                        <th>Due Date</th>
                                                        <th>Submissions</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($assignments as $assignment): ?>
                                                    <tr>
                                                        <td><?= esc($assignment['title']) ?></td>
                                                        <td><?= esc($assignment['due']) ?></td>
                                                        <td>
                                                            <span class="badge bg-info"><?= $assignment['submissions'] ?> Submitted</span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary">
                                                                <i class="bi bi-eye"></i> View
                                                            </button>
                                                            <button class="btn btn-sm btn-warning">
                                                                <i class="bi bi-pencil"></i> Edit
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
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
