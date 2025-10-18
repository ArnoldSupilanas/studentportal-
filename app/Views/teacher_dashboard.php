<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Teacher Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-chalkboard-teacher me-2"></i>Teacher Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('announcements') ?>">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('auth/logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-success text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-chalkboard-teacher me-3"></i>
                        <?= $page_title ?? 'Welcome, Teacher!' ?>
                    </h1>
                    <p class="lead"><?= $description ?? 'Manage your courses and students from this dashboard.' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-chalkboard-teacher fa-5x text-success mb-4"></i>
                        <h2 class="text-success mb-3">Teacher Dashboard</h2>
                        <p class="lead">Welcome to your teaching dashboard! Here you can manage your courses, students, and teaching materials.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <i class="fas fa-book fa-2x text-primary mb-2"></i>
                                        <h5>My Courses</h5>
                                        <p class="text-muted">Manage your courses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                                        <h5>Students</h5>
                                        <p class="text-muted">View your students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                                        <h5>Analytics</h5>
                                        <p class="text-muted">View reports</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?= base_url('announcements') ?>" class="btn btn-success btn-lg me-3">
                                <i class="fas fa-bullhorn me-2"></i>View Announcements
                            </a>
                            <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-success btn-lg">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Dashboard</h5>
                    <p class="mb-0">Empowering teachers with technology.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2025 LMS. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>