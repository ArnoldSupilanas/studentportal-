<?php 
// Include header template
$headerData = [
    'title' => $title ?? 'Materials Management',
    'role' => $role ?? 'admin',
    'is_logged_in' => session()->get('is_logged_in') || session()->get('logged_in'),
    'name' => session()->get('first_name') ?? 'User'
];
echo view('templates/header', $headerData);
?>

<body>
    <!-- Navigation is now in header template -->

    <!-- Page Header -->
    <div class="container-fluid bg-primary text-white py-4 mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-2">
                        <i class="bi bi-folder2-open me-2"></i>
                        <?= $page_title ?? 'Course Materials Management' ?>
                    </h1>
                    <p class="mb-0"><?= $description ?? 'Manage all course materials' ?></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Materials Overview -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-file-earmark-text fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0"><?= isset($materials) ? count($materials) : 0 ?></h3>
                                <p class="mb-0">Total Materials</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-book fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">0</h3>
                                <p class="mb-0">Courses with Materials</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-cloud-upload fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">Upload</h3>
                                <p class="mb-0">Add New Material</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            All Materials
                            <span class="badge bg-light text-success ms-2"><?= isset($materials) ? count($materials) : 0 ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($materials) && !empty($materials)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-file-earmark me-2"></i>File Name</th>
                                            <th><i class="bi bi-book me-2"></i>Course</th>
                                            <th><i class="bi bi-calendar me-2"></i>Upload Date</th>
                                            <th><i class="bi bi-hdd me-2"></i>File Size</th>
                                            <th><i class="bi bi-gear me-2"></i>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materials as $material): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <?php
                                                            $extension = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                                            $iconClass = 'bi-file-earmark';
                                                            $iconColor = 'text-muted';
                                                            
                                                            switch (strtolower($extension)) {
                                                                case 'pdf':
                                                                    $iconClass = 'bi-file-earmark-pdf';
                                                                    $iconColor = 'text-danger';
                                                                    break;
                                                                case 'doc':
                                                                case 'docx':
                                                                    $iconClass = 'bi-file-earmark-word';
                                                                    $iconColor = 'text-primary';
                                                                    break;
                                                                case 'ppt':
                                                                case 'pptx':
                                                                    $iconClass = 'bi-file-earmark-ppt';
                                                                    $iconColor = 'text-warning';
                                                                    break;
                                                                case 'xls':
                                                                case 'xlsx':
                                                                    $iconClass = 'bi-file-earmark-excel';
                                                                    $iconColor = 'text-success';
                                                                    break;
                                                                case 'txt':
                                                                    $iconClass = 'bi-file-earmark-text';
                                                                    $iconColor = 'text-info';
                                                                    break;
                                                                case 'jpg':
                                                                case 'jpeg':
                                                                case 'png':
                                                                case 'gif':
                                                                    $iconClass = 'bi-file-earmark-image';
                                                                    $iconColor = 'text-warning';
                                                                    break;
                                                                case 'zip':
                                                                case 'rar':
                                                                    $iconClass = 'bi-file-earmark-zip';
                                                                    $iconColor = 'text-secondary';
                                                                    break;
                                                            }
                                                            ?>
                                                            <i class="bi <?= $iconClass ?> <?= $iconColor ?> me-2"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <strong><?= esc($material['file_name']) ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?= strtoupper($extension) ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">Course ID: <?= $material['course_id'] ?></span>
                                                </td>
                                                <td>
                                                    <small><?= date('M d, Y h:i A', strtotime($material['created_at'])) ?></small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $filePath = WRITEPATH . $material['file_path'];
                                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                    if ($fileSize < 1024) {
                                                        echo $fileSize . ' B';
                                                    } elseif ($fileSize < 1024 * 1024) {
                                                        echo number_format($fileSize / 1024, 2) . ' KB';
                                                    } else {
                                                        echo number_format($fileSize / (1024 * 1024), 2) . ' MB';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                                           class="btn btn-outline-primary" 
                                                           title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        <a href="<?= base_url('materials/delete/' . $material['id']) ?>" 
                                                           class="btn btn-outline-danger" 
                                                           title="Delete"
                                                           onclick="return confirm('Are you sure you want to delete this material?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Materials Found</h5>
                                <p class="text-muted">No materials have been uploaded yet.</p>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-cloud-upload me-2"></i>Upload First Material
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><h6 class="dropdown-header">Select Course</h6></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/upload/1') ?>">Course 1</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/upload/2') ?>">Course 2</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/upload/3') ?>">Course 3</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="dropdown">
                                    <button class="btn btn-primary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-cloud-upload me-2"></i>Upload Material
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li><h6 class="dropdown-header">Select Course</h6></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/upload/1') ?>">Course 1</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/upload/2') ?>">Course 2</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/upload/3') ?>">Course 3</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/courses') ?>">Manage Courses</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="dropdown">
                                    <button class="btn btn-info w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-eye me-2"></i>View Materials
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li><h6 class="dropdown-header">Select Course</h6></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/view/1') ?>">Course 1 Materials</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/view/2') ?>">Course 2 Materials</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/view/3') ?>">Course 3 Materials</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?= base_url('materials/dashboard') ?>">Student View</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary w-100">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('admin/courses') ?>" class="btn btn-success w-100">
                                    <i class="bi bi-book me-2"></i>Manage Courses
                                </a>
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
