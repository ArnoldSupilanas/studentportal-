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
                    <div class="card-header bg-warning text-dark">
                        <h2 class="mb-0">
                            <i class="bi bi-pencil"></i> <?= esc($page_title) ?>
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

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="/index.php/teacher/updateStudent/<?= esc($student['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="student_id" class="form-label">Student ID</label>
                                        <input type="text" class="form-control" id="student_id" value="#<?= esc($student['id']) ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?= esc($student['name']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($student['email']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="course" class="form-label">Course *</label>
                                        <select class="form-select" id="course" name="course" required>
                                            <option value="">Select Course</option>
                                            <option value="Mathematics 101" <?= $student['course'] == 'Mathematics 101' ? 'selected' : '' ?>>Mathematics 101</option>
                                            <option value="Computer Science" <?= $student['course'] == 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
                                            <option value="Physics 201" <?= $student['course'] == 'Physics 201' ? 'selected' : '' ?>>Physics 201</option>
                                            <option value="Chemistry 101" <?= $student['course'] == 'Chemistry 101' ? 'selected' : '' ?>>Chemistry 101</option>
                                            <option value="Biology 201" <?= $student['course'] == 'Biology 201' ? 'selected' : '' ?>>Biology 201</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="grade" class="form-label">Current Grade *</label>
                                        <select class="form-select" id="grade" name="grade" required>
                                            <option value="">Select Grade</option>
                                            <option value="A+" <?= $student['grade'] == 'A+' ? 'selected' : '' ?>>A+</option>
                                            <option value="A" <?= $student['grade'] == 'A' ? 'selected' : '' ?>>A</option>
                                            <option value="A-" <?= $student['grade'] == 'A-' ? 'selected' : '' ?>>A-</option>
                                            <option value="B+" <?= $student['grade'] == 'B+' ? 'selected' : '' ?>>B+</option>
                                            <option value="B" <?= $student['grade'] == 'B' ? 'selected' : '' ?>>B</option>
                                            <option value="B-" <?= $student['grade'] == 'B-' ? 'selected' : '' ?>>B-</option>
                                            <option value="C+" <?= $student['grade'] == 'C+' ? 'selected' : '' ?>>C+</option>
                                            <option value="C" <?= $student['grade'] == 'C' ? 'selected' : '' ?>>C</option>
                                            <option value="C-" <?= $student['grade'] == 'C-' ? 'selected' : '' ?>>C-</option>
                                            <option value="D" <?= $student['grade'] == 'D' ? 'selected' : '' ?>>D</option>
                                            <option value="F" <?= $student['grade'] == 'F' ? 'selected' : '' ?>>F</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="Active" <?= $student['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= $student['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                            <option value="Suspended" <?= $student['status'] == 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                                            <option value="Withdrawn" <?= $student['status'] == 'Withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="(123) 456-7890">
                                    </div>

                                    <div class="mb-3">
                                        <label for="emergency_contact" class="form-label">Emergency Contact</label>
                                        <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" placeholder="Parent/Guardian Name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Academic Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Enter any academic notes, observations, or concerns..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Performance Metrics</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="attendance" class="form-label">Attendance Rate (%)</label>
                                                <input type="number" class="form-control" id="attendance" name="attendance" min="0" max="100" placeholder="95">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="participation" class="form-label">Participation Score (1-10)</label>
                                                <input type="number" class="form-control" id="participation" name="participation" min="1" max="10" placeholder="8">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="assignments_completed" class="form-label">Assignments Completed (%)</label>
                                                <input type="number" class="form-control" id="assignments_completed" name="assignments_completed" min="0" max="100" placeholder="90">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="/index.php/teacher/students" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Back to Students
                                    </a>
                                    <a href="/index.php/teacher/viewStudent/<?= esc($student['id']) ?>" class="btn btn-info">
                                        <i class="bi bi-person"></i> View Student
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" onclick="if(confirm('Are you sure you want to reset this form?')) { window.location.reload(); }">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const course = document.getElementById('course').value;
            const grade = document.getElementById('grade').value;
            const status = document.getElementById('status').value;

            if (!name || !email || !course || !grade || !status) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 6) {
                value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
            } else if (value.length >= 3) {
                value = value.slice(0, 3) + '-' + value.slice(3);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
