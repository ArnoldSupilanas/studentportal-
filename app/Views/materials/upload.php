<?php 
// Include header template
$headerData = [
    'title' => $title ?? 'Upload Materials',
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
                        <i class="bi bi-cloud-upload me-2"></i>
                        <?= $page_title ?? 'Upload Course Materials' ?>
                    </h1>
                    <p class="mb-0"><?= $description ?? 'Upload and manage course materials' ?></p>
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

        <div class="row">
            <!-- Upload Form -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Upload New Material
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('materials/upload/' . $course['id']) ?>" 
                              method="post" 
                              enctype="multipart/form-data"
                              class="needs-validation"
                              novalidate>
                            
                            <!-- CSRF Token -->
                            <?= csrf_field() ?>

                            <!-- File Upload -->
                            <div class="mb-4">
                                <label for="material_file" class="form-label fw-bold">
                                    <i class="bi bi-file-earmark me-2"></i>
                                    Select File
                                </label>
                                <input type="file" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('material_file')) ? 'is-invalid' : '' ?>" 
                                       id="material_file" 
                                       name="material_file" 
                                       required>
                                <?php if (isset($validation) && $validation->hasError('material_file')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('material_file') ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-text">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Allowed file types: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG, GIF, ZIP, RAR<br>
                                        Maximum file size: 10MB
                                    </small>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-cloud-upload me-2"></i>
                                    Upload Material
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Upload Guidelines -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Upload Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold text-primary">File Requirements:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                File size must be less than 10MB
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Supported formats: PDF, DOC, DOCX, PPT, PPTX
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Images: JPG, JPEG, PNG, GIF
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Archives: ZIP, RAR
                            </li>
                        </ul>

                        <h6 class="fw-bold text-primary mt-3">Best Practices:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning me-2"></i>
                                Use descriptive file names
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning me-2"></i>
                                Organize materials by topic
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning me-2"></i>
                                Ensure files are virus-free
                            </li>
                        </ul>

                        <div class="alert alert-light border mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-2"></i>
                                All uploaded materials are automatically scanned and stored securely.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Materials -->
        <?php if (isset($materials) && !empty($materials)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-folder2-open me-2"></i>
                                Existing Materials (<?= count($materials) ?>)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-file-earmark me-2"></i>File Name</th>
                                            <th><i class="bi bi-calendar me-2"></i>Upload Date</th>
                                            <th><i class="bi bi-hdd me-2"></i>File Size</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materials as $material): ?>
                                            <tr>
                                                <td>
                                                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                    <?= esc($material['file_name']) ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= date('M d, Y h:i A', strtotime($material['created_at'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $filePath = WRITEPATH . $material['file_path'];
                                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                    echo number_format($fileSize / 1024, 2) . ' KB';
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        <?php if (in_array($role, ['admin', 'teacher'])): ?>
                                                            <a href="<?= base_url('materials/delete/' . $material['id']) ?>" 
                                                               class="btn btn-sm btn-outline-danger" 
                                                               title="Delete"
                                                               onclick="return confirm('Are you sure you want to delete this material?')">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
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
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php echo view('templates/footer'); ?>

    <!-- JavaScript for Form Validation -->
    <script>
    // Bootstrap form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // File size validation
    document.getElementById('material_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        
        if (file && file.size > maxSize) {
            e.target.setCustomValidity('File size must be less than 10MB.');
        } else {
            e.target.setCustomValidity('');
        }
    });
    </script>
</body>
</html>
