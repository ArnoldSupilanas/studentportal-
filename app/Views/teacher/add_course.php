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
                <a class="nav-link" href="/index.php/teacher/courses">
                    <i class="bi bi-book"></i> Courses
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
                            <i class="bi bi-plus-circle"></i> <?= esc($page_title) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted"><?= esc($description ?? 'Create and manage your courses here.') ?></p>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?= base_url('teacher/addCourse') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="course_name" name="course_name" required>
                                <div class="form-text">Enter the name of the course (e.g., Mathematics 101)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="course_description" class="form-label">Course Description</label>
                                <textarea class="form-control" id="course_description" name="course_description" rows="4"></textarea>
                                <div class="form-text">Provide a brief description of the course content and objectives.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="course_code" class="form-label">Course Code</label>
                                <input type="text" class="form-control" id="course_code" name="course_code" placeholder="e.g., MATH101">
                                <div class="form-text">Optional: Enter a course code for reference.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="credits" class="form-label">Credits</label>
                                <input type="number" class="form-control" id="credits" name="credits" min="1" max="6" value="3">
                                <div class="form-text">Number of credits for this course.</div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('teacher/courses') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Create Course
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
