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
                <a class="nav-link" href="/index.php/teacher/students">
                    <i class="bi bi-people"></i> Students
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
                            <i class="bi bi-person-circle"></i> <?= esc($page_title) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted"><?= esc($description) ?></p>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                        <i class="bi bi-person-fill" style="font-size: 4rem; color: #6c757d;"></i>
                                    </div>
                                    <h4 class="mt-3"><?= esc($student['name']) ?></h4>
                                    <span class="badge bg-success"><?= esc($student['status']) ?></span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Student Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Student ID:</strong></div>
                                            <div class="col-sm-9">#<?= esc($student['id']) ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Email:</strong></div>
                                            <div class="col-sm-9">
                                                <a href="mailto:<?= esc($student['email']) ?>"><?= esc($student['email']) ?></a>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Course:</strong></div>
                                            <div class="col-sm-9"><?= esc($student['course']) ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Grade:</strong></div>
                                            <div class="col-sm-9">
                                                <span class="badge bg-primary"><?= esc($student['grade']) ?></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Status:</strong></div>
                                            <div class="col-sm-9">
                                                <span class="badge bg-success"><?= esc($student['status']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($student['details'])): ?>
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-file-text"></i> Additional Notes</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><?= esc($student['details']) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="/index.php/teacher/students" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Students
                            </a>
                            <a href="/index.php/teacher/emailStudent/<?= esc($student['id']) ?>" class="btn btn-primary">
                                <i class="bi bi-envelope"></i> Send Email
                            </a>
                            <a href="/index.php/teacher/editStudent/<?= esc($student['id']) ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit Student
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
