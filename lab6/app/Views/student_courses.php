<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">Student Portal</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('student/dashboard') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url('student/courses') ?>">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('auth/logout') ?>">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>My Courses</h1>
        <p>View and manage your enrolled courses.</p>
        
        <!-- Course Selection -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2"></i>
                    Select Course
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <label for="courseSelect" class="form-label">Choose a course to enroll:</label>
                        <select class="form-select" id="courseSelect">
                            <option value="">Select a course...</option>
                            <?php if (!empty($courses)): ?>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course['id'] ?>">
                                        <?= esc($course['title']) ?> - <?= esc($course['code']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-success w-100" onclick="enrollInCourse()">
                            <i class="fas fa-plus-circle me-2"></i>Enroll Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enrolled Courses -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-book-open me-2"></i>
                    Enrolled Courses
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($enrolled_courses)): ?>
                    <div class="row">
                        <?php foreach ($enrolled_courses as $course): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">
                                            <?= esc($course['title']) ?>
                                        </h6>
                                        <p class="card-text">
                                            <small class="text-muted"><?= esc($course['title']) ?></small><br>
                                            <?= esc($course['description']) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Enrolled: <?= date('M d, Y', strtotime($course['enrollment_date'])) ?>
                                            </small>
                                            <button class="btn btn-sm btn-outline-danger" onclick="unenrollFromCourse(<?= $course['course_id'] ?>)">
                                                <i class="fas fa-times me-1"></i>Unenroll
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <p class="text-muted">You haven't enrolled in any courses yet. Select a course below to get started.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Available Courses -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Available Courses
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($courses)): ?>
                    <div class="row">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <?= esc($course['title']) ?>
                                        </h6>
                                        <p class="card-text">
                                            <small class="text-muted"><?= esc($course['code']) ?></small><br>
                                            <?= esc($course['description']) ?>
                                        </p>
                                        <button class="btn btn-sm btn-outline-primary" onclick="selectCourse(<?= $course['id'] ?>)">
                                            <i class="fas fa-plus me-1"></i>Select
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No courses available at the moment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectCourse(courseId) {
            document.getElementById('courseSelect').value = courseId;
            document.getElementById('courseSelect').focus();
        }
        
        function enrollInCourse() {
            const courseId = document.getElementById('courseSelect').value;
            if (!courseId) {
                alert('Please select a course first.');
                return;
            }
            
            const courseName = document.getElementById('courseSelect').options[document.getElementById('courseSelect').selectedIndex].text;
            
            if (confirm('Are you sure you want to enroll in: ' + courseName + '?')) {
                // Make actual AJAX enrollment call
                fetch('<?= base_url('course/enroll') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `course_id=${courseId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Enrollment successful!');
                        // Refresh the page to show updated enrolled courses
                        window.location.reload();
                    } else {
                        alert(data.message || 'Enrollment failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during enrollment. Please try again.');
                });
            }
        }
        
        function unenrollFromCourse(courseId) {
            if (confirm('Are you sure you want to unenroll from this course?')) {
                fetch('<?= base_url('course/unenroll') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `course_id=${courseId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Unenrollment successful!');
                        // Refresh the page to show updated enrolled courses
                        window.location.reload();
                    } else {
                        alert(data.message || 'Unenrollment failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during unenrollment. Please try again.');
                });
            }
        }
        
        function selectCourse(courseId) {
            document.getElementById('courseSelect').value = courseId;
        }
    </script>
</body>
</html>
