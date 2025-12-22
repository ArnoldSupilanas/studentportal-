<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .text-decoration-none:hover .card {
            background-color: #f8f9fa !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-user-shield me-2"></i>Admin Dashboard
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
    <div class="bg-danger text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-user-shield me-3"></i>
                        <?= $page_title ?? 'Welcome, Admin!' ?>
                    </h1>
                    <p class="lead"><?= $description ?? 'Manage the entire system from this administrative dashboard.' ?></p>
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
                        <i class="fas fa-user-shield fa-5x text-danger mb-4"></i>
                        <h2 class="text-danger mb-3">Admin Dashboard</h2>
                        <p class="lead">Welcome to the administrative dashboard! Here you can manage the entire system, users, and system settings.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <a href="<?= base_url('admin/users') ?>" class="text-decoration-none">
                                    <div class="card border-0 bg-light h-100 hover-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                            <h5>User Management</h5>
                                            <p class="text-muted">Manage all users</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light h-100 hover-card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cog fa-2x text-success mb-2"></i>
                                        <h5>System Settings</h5>
                                        <p class="text-muted">Configure system</p>
                                        <button class="btn btn-success mt-2" onclick="goToSettings()">
                                            <i class="fas fa-cog me-1"></i>Open Settings
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?= base_url('admin/reports') ?>" class="text-decoration-none">
                                    <div class="card border-0 bg-light h-100 hover-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-chart-bar fa-2x text-warning mb-2"></i>
                                            <h5>Reports</h5>
                                            <p class="text-muted">View system reports</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button class="btn btn-danger btn-lg me-3" onclick="goToAnnouncements()">
                                <i class="fas fa-bullhorn me-2"></i>View Announcements
                            </button>
                            <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-danger btn-lg">
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
                    <h5><i class="fas fa-user-shield me-2"></i>Admin Dashboard</h5>
                    <p class="mb-0">System administration and management.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2025 LMS. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Functional button handlers
        function goToSettings() {
            console.log('Settings button clicked');
            window.location.href = '<?= base_url('admin/settings') ?>';
        }
        
        function goToAnnouncements() {
            console.log('Announcements button clicked');
            window.location.href = '<?= base_url('announcements') ?>';
        }
        
        // Ensure all buttons work properly
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin Dashboard loaded - buttons are functional');
        });
    </script>
</body>
</html>