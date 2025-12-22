<?php 
// Include header template
$headerData = [
    'title' => $title ?? 'Course Materials',
    'role' => $role ?? 'student',
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
                        <?= $page_title ?? 'Course Materials' ?>
                    </h1>
                    <p class="mb-0"><?= $description ?? 'View and download course materials' ?></p>
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

        <!-- Course Information -->
        <?php if (isset($course)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-book me-2"></i>
                                <?= esc($course['title']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-0"><?= esc($course['description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Materials Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Available Materials 
                            <?php if (isset($materials)): ?>
                                <span class="badge bg-light text-success ms-2"><?= count($materials) ?></span>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($materials) && !empty($materials)): ?>
                            <!-- Materials Grid View -->
                            <div class="row">
                                <?php foreach ($materials as $material): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 border-light shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0">
                                                        <div class="file-icon bg-primary bg-gradient text-white rounded p-3">
                                                            <?php
                                                            $extension = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                                            $iconClass = 'bi-file-earmark';
                                                            
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
                                                                default:
                                                                    $iconClass = 'bi-file-earmark';
                                                                    $iconColor = 'text-muted';
                                                            }
                                                            ?>
                                                            <i class="bi <?= $iconClass ?> <?= $iconColor ?> fa-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="card-title text-truncate" title="<?= esc($material['file_name']) ?>">
                                                            <?= esc($material['file_name']) ?>
                                                        </h6>
                                                        <p class="card-text text-muted small">
                                                            <i class="bi bi-calendar me-1"></i>
                                                            <?= date('M d, Y h:i A', strtotime($material['created_at'])) ?>
                                                        </p>
                                                        <p class="card-text text-muted small">
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
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-secondary"><?= strtoupper($extension) ?></span>
                                                    <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                                       class="btn btn-sm btn-primary"
                                                       title="Download <?= esc($material['file_name']) ?>">
                                                        <i class="bi bi-download me-1"></i>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Table View (Alternative) -->
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 text-muted">
                                        <i class="bi bi-list-ul me-2"></i>
                                        List View
                                    </h6>
                                    <small class="text-muted">Click on file name to download</small>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th><i class="bi bi-file-earmark me-2"></i>File Name</th>
                                                <th><i class="bi bi-calendar me-2"></i>Date</th>
                                                <th><i class="bi bi-hdd me-2"></i>Size</th>
                                                <th><i class="bi bi-tag me-2"></i>Type</th>
                                                <th class="text-center"><i class="bi bi-download me-2"></i>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($materials as $material): ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                                           class="text-decoration-none text-primary"
                                                           title="Download <?= esc($material['file_name']) ?>">
                                                            <i class="bi bi-file-earmark me-2"></i>
                                                            <?= esc($material['file_name']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">
                                                            <?= date('M d, Y', strtotime($material['created_at'])) ?>
                                                        </span>
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
                                                        <span class="badge bg-light text-dark">
                                                            <?= strtoupper(pathinfo($material['file_name'], PATHINFO_EXTENSION)) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                                           class="btn btn-sm btn-outline-primary"
                                                           title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        <?php else: ?>
                            <!-- No Materials Message -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-3">No Materials Available</h5>
                                <p class="text-muted mb-4">
                                    Your instructor hasn't uploaded any materials for this course yet.
                                </p>
                                <?php if ($role === 'student'): ?>
                                    <div class="alert alert-info d-inline-block">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Materials will appear here once your instructor uploads them.
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Materials (for Admin/Teacher) -->
        <?php if (in_array($role, ['admin', 'teacher'])): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-cloud-upload me-2"></i>
                                Upload New Material
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Upload additional materials for this course. Students will be able to download them immediately.
                            </p>
                            <a href="<?= base_url('materials/upload/' . $course['id']) ?>" 
                               class="btn btn-warning">
                                <i class="bi bi-cloud-upload me-2"></i>
                                Upload Material
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php echo view('templates/footer'); ?>

    <!-- JavaScript -->
    <script>
    // Add hover effects to cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow');
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow');
            });
        });

        // Add click feedback to download buttons
        const downloadButtons = document.querySelectorAll('a[href*="download"]');
        downloadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const fileName = this.getAttribute('title') || 'file';
                console.log('Downloading:', fileName);
            });
        });
    });
    </script>
</body>
</html>
