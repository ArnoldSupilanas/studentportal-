<?php 
// Include header template
$headerData = [
    'title' => 'My Materials - Student Portal',
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
                        My Course Materials
                    </h1>
                    <p class="mb-0">Access and download materials from your enrolled courses</p>
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

        <!-- Materials Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-book fa-2x mb-2"></i>
                        <h4><?= $total_courses ?? 0 ?></h4>
                        <p class="mb-0">Enrolled Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-text fa-2x mb-2"></i>
                        <h4><?= $total_materials ?? 0 ?></h4>
                        <p class="mb-0">Total Materials</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history fa-2x mb-2"></i>
                        <h4><?= $recent_materials ?? 0 ?></h4>
                        <p class="mb-0">Recent Uploads</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials by Course -->
        <?php if (isset($course_materials) && !empty($course_materials)): ?>
            <?php foreach ($course_materials as $course_id => $course_data): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-book me-2"></i>
                                        <?= esc($course_data['course']['title']) ?>
                                    </h5>
                                    <div>
                                        <span class="badge bg-light text-primary me-2">
                                            <?= count($course_data['materials']) ?> Materials
                                        </span>
                                        <a href="<?= base_url('materials/view/' . $course_id) ?>" 
                                           class="btn btn-sm btn-outline-light">
                                            <i class="bi bi-eye me-1"></i>View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($course_data['materials'])): ?>
                                    <!-- Recent Materials (max 3) -->
                                    <div class="row">
                                        <?php $materials_to_show = array_slice($course_data['materials'], 0, 3); ?>
                                        <?php foreach ($materials_to_show as $material): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card h-100 border-light shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-shrink-0">
                                                                <?php
                                                                $extension = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                                                $iconClass = 'bi-file-earmark';
                                                                $iconColor = 'text-primary';
                                                                
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
                                                                <i class="bi <?= $iconClass ?> <?= $iconColor ?> fa-2x"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="card-title text-truncate" title="<?= esc($material['file_name']) ?>">
                                                                    <?= esc($material['file_name']) ?>
                                                                </h6>
                                                                <p class="card-text text-muted small">
                                                                    <i class="bi bi-calendar me-1"></i>
                                                                    <?= date('M d, Y', strtotime($material['created_at'])) ?>
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

                                    <!-- Show More Link -->
                                    <?php if (count($course_data['materials']) > 3): ?>
                                        <div class="text-center mt-3">
                                            <a href="<?= base_url('materials/view/' . $course_id) ?>" 
                                               class="btn btn-outline-primary">
                                                <i class="bi bi-arrow-right-circle me-2"></i>
                                                View All <?= count($course_data['materials']) ?> Materials
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No materials available for this course yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- No Materials -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-folder-x fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Course Materials Available</h4>
                            <p class="text-muted mb-4">
                                You haven't enrolled in any courses yet, or your instructors haven't uploaded materials.
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Getting Started
                                        </h6>
                                        <p class="mb-2">
                                            <strong>Enroll in courses</strong> to access course materials
                                        </p>
                                        <p class="mb-0">
                                            <strong>Contact your instructors</strong> if you expect materials but don't see any
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary mt-3">
                                <i class="bi bi-arrow-left me-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <?php if (isset($course_materials) && !empty($course_materials)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-lightning me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Dashboard
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url('materials') ?>" class="btn btn-outline-success w-100">
                                        <i class="bi bi-folder2-open me-2"></i>
                                        All Materials
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-info w-100" onclick="window.print()">
                                        <i class="bi bi-printer me-2"></i>
                                        Print List
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-warning w-100" onclick="refreshMaterials()">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Refresh
                                    </button>
                                </div>
                            </div>
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
    // Refresh materials
    function refreshMaterials() {
        location.reload();
    }

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
