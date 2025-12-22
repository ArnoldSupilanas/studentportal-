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
                    <div class="card-header bg-info text-white">
                        <h2 class="mb-0">
                            <i class="bi bi-graph-up"></i> <?= esc($page_title) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted"><?= esc($description) ?></p>
                        
                        <!-- Statistics Overview -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="text-primary"><?= $stats['total_users'] ?></h3>
                                        <p class="mb-0">Total Users</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="text-success"><?= $stats['total_students'] ?></h3>
                                        <p class="mb-0">Total Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="text-warning"><?= $stats['total_teachers'] ?></h3>
                                        <p class="mb-0">Total Teachers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="text-info"><?= $stats['total_courses'] ?></h3>
                                        <p class="mb-0">Total Courses</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Stats -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bi bi-book"></i> Course Statistics</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Active Courses:</strong> <?= $stats['active_courses'] ?></p>
                                        <p><strong>Total Assignments:</strong> <?= $stats['total_assignments'] ?></p>
                                        <p><strong>Pending Approvals:</strong> <?= $stats['pending_approvals'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bi bi-clock-history"></i> Recent Activity</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group">
                                            <?php foreach ($recent_activity as $activity): ?>
                                            <div class="list-group-item">
                                                <i class="bi bi-arrow-right-circle text-primary"></i>
                                                <?= esc($activity['action']) ?> by <?= esc($activity['user']) ?>
                                                <small class="text-muted float-end"><?= esc($activity['time']) ?></small>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Export Options -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bi bi-download"></i> Export Reports</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-primary">
                                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                                            </button>
                                            <button class="btn btn-success">
                                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                                            </button>
                                            <button class="btn btn-info">
                                                <i class="bi bi-printer"></i> Print Report
                                            </button>
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
