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
                        
                                                
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <?php foreach ($courses as $course): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= esc($course['name']) ?></h5>
                                        <p class="card-text">
                                            <i class="bi bi-people"></i> <?= $course['students'] ?> Students<br>
                                            <i class="bi bi-graph-up"></i> <?= $course['progress'] ?>% Complete
                                        </p>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" style="width: <?= $course['progress'] ?>%"></div>
                                        </div>
                                        <a href="<?= base_url('teacher/manageCourse/' . $course['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-arrow-right"></i> Manage Course
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-3">
                            <a href="<?= base_url('teacher/addCourse') ?>" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Add New Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
