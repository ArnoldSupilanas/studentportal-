<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h1 class="card-title h3 mb-2">
                            <i class="bi bi-book me-2"></i><?= esc($page_title) ?>
                        </h1>
                        <p class="card-text mb-0"><?= esc($description) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Course Information -->
        <?php if (isset($course)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-mortarboard me-2"></i><?= esc($course['title']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Course Code:</strong> <?= esc($course['course_code'] ?? 'N/A') ?></p>
                            <p class="mb-0"><?= esc($course['description'] ?? 'No description available') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Materials List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-folder2-open me-2"></i>
                            Course Materials
                            <?php if (isset($materials)): ?>
                                <span class="badge bg-light text-dark ms-2"><?= count($materials) ?> items</span>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($materials) && !empty($materials)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-file-earmark me-2"></i>File Name</th>
                                            <th><i class="bi bi-calendar me-2"></i>Upload Date</th>
                                            <th><i class="bi bi-hdd me-2"></i>File Size</th>
                                            <th class="text-center"><i class="bi bi-download me-2"></i>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materials as $material): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php
                                                        // Determine file icon based on extension
                                                        $fileExtension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                                                        $iconClass = 'bi-file-earmark';
                                                        $iconColor = 'text-secondary';
                                                        
                                                        switch ($fileExtension) {
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
                                                                $iconClass = 'bi-file-earmark-slides';
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
                                                                $iconColor = 'text-dark';
                                                                break;
                                                        }
                                                        ?>
                                                        <i class="bi <?= $iconClass ?> <?= $iconColor ?> me-2 fs-5"></i>
                                                        <span><?= esc($material['file_name']) ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        <?= date('M d, Y h:i A', strtotime($material['created_at'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $filePath = WRITEPATH . $material['file_path'];
                                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                    
                                                    if ($fileSize >= 1048576) {
                                                        echo number_format($fileSize / 1048576, 2) . ' MB';
                                                    } elseif ($fileSize >= 1024) {
                                                        echo number_format($fileSize / 1024, 2) . ' KB';
                                                    } else {
                                                        echo $fileSize . ' B';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                                       class="text-primary text-decoration-none"
                                                       title="Download <?= esc($material['file_name']) ?>">
                                                        <i class="bi bi-download me-1"></i>
                                                        Download
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Materials Available</h5>
                                <p class="text-muted">No materials have been uploaded for this course yet.</p>
                                <p class="text-muted">
                                    <small>Check back later or contact your instructor for more information.</small>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row mt-4">
            <div class="col-12">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
