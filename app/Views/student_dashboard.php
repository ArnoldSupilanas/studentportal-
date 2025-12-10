<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">Student Portal</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('announcements') ?>">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('student/courses') ?>">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('student/assignments') ?>">Assignments</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('student/grades') ?>">Grades</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('auth/logout') ?>">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Student Dashboard</h1>
        <p>Welcome to your student dashboard!</p>
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Enrolled Courses Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">My Enrolled Courses</h5>
                    </div>
                    <div class="card-body enrolled-courses-list">
                        <?php if (!empty($enrolled_courses)): ?>
                            <div class="list-group">
                                <?php foreach ($enrolled_courses as $course): ?>
                                    <div class="list-group-item" id="enrolled-course-<?= $course['course_id'] ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <div>
                                                <h6 class="mb-1"><?= esc($course['title']) ?></h6>
                                                <p class="mb-1 text-muted"><?= esc(substr($course['description'], 0, 100)) ?>...</p>
                                                <small class="text-muted">
                                                    Progress: <?= $course['progress'] ?>% | 
                                                    Status: <span class="badge bg-success"><?= $course['status'] ?></span>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">Enrolled: <?= date('M j, Y', strtotime($course['enrollment_date'])) ?></small>
                                                <br>
                                                <button class="btn btn-sm btn-outline-danger mt-2 unenroll-btn" data_course_id="<?= $course['course_id'] ?>">Drop</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">You are not enrolled in any courses yet.</p>
                            <p>Browse the available courses below to get started!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Available Courses Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Available Courses</h5>
                    </div>
                    <div class="card-body available-courses-list">
                        <?php if (!empty($available_courses)): ?>
                            <div class="list-group">
                                <?php foreach ($available_courses as $course): ?>
                                    <div class="list-group-item" id="available-course-<?= $course['id'] ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <div>
                                                <h6 class="mb-1"><?= esc($course['title']) ?></h6>
                                                <p class="mb-1 text-muted"><?= esc(substr($course['description'], 0, 100)) ?>...</p>
                                                <small class="text-muted">Instructor ID: <?= $course['instructor_id'] ?></small>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-sm btn-primary enroll-btn" 
                                                        data_course_id="<?= $course['id'] ?>"
                                                        data_course_title="<?= esc($course['title']) ?>">
                                                    Enroll
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No new courses available at the moment.</p>
                            <p>Check back later for new course offerings!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title enrolled-courses-stat"><?= count($enrolled_courses) ?></h5>
                        <p class="card-text">Enrolled Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title available-courses-stat"><?= count($available_courses) ?></h5>
                        <p class="card-text">Available Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">0</h5>
                        <p class="card-text">Pending Assignments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">A</h5>
                        <p class="card-text">Current GPA</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for AJAX Enrollment -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle enrollment button clicks
            $('.enroll-btn').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const courseId = $button.data('course_id');
                const courseTitle = $button.data('course_title');
                
                if (confirm('Are you sure you want to enroll in "' + courseTitle + '"?')) {
                    enrollCourse(courseId, $button);
                }
            });
            
            // Handle unenrollment button clicks
            $('.unenroll-btn').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const courseId = $button.data('course_id');
                
                if (confirm('Are you sure you want to drop this course?')) {
                    unenrollCourse(courseId, $button);
                }
            });
        });
        
        function enrollCourse(courseId, $button) {
            $.post('<?= base_url('course/enroll') ?>', {
                course_id: courseId
            }, function(response) {
                if (response.success) {
                    // Show success alert
                    showAlert(response.message, 'success');
                    
                    // Hide the enroll button
                    $button.hide();
                    
                    // Dynamically add course to enrolled courses list
                    addCourseToEnrolledList(response.course_data);
                    
                    // Update stats
                    updateStats();
                } else {
                    // Show error alert
                    showAlert(response.message, 'danger');
                }
            }, 'json')
            .fail(function() {
                showAlert('An error occurred while enrolling. Please try again.', 'danger');
            });
        }
        
        function unenrollCourse(courseId, $button) {
            $.post('<?= base_url('course/unenroll') ?>', {
                course_id: courseId
            }, function(response) {
                if (response.success) {
                    // Show success alert
                    showAlert(response.message, 'success');
                    
                    // Remove course from enrolled list
                    removeCourseFromEnrolledList(courseId);
                    
                    // Update stats
                    updateStats();
                } else {
                    // Show error alert
                    showAlert(response.message, 'danger');
                }
            }, 'json')
            .fail(function() {
                showAlert('An error occurred while dropping the course. Please try again.', 'danger');
            });
        }
        
        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Add alert to the top of the container
            $('.container').prepend(alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        }
        
        function addCourseToEnrolledList(courseData) {
            const enrolledCourseHtml = `
                <div class="list-group-item" id="enrolled-course-${courseData.course_id}">
                    <div class="d-flex w-100 justify-content-between">
                        <div>
                            <h6 class="mb-1">${courseData.title}</h6>
                            <p class="mb-1 text-muted">${courseData.description ? courseData.description.substring(0, 100) + '...' : ''}</p>
                            <small class="text-muted">
                                Progress: 0% | 
                                Status: <span class="badge bg-success">enrolled</span>
                            </small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Enrolled: Just now</small>
                            <br>
                            <button class="btn btn-sm btn-outline-danger mt-2 unenroll-btn" data_course_id="${courseData.course_id}">Drop</button>
                        </div>
                    </div>
                </div>
            `;
            
            // Add to enrolled courses list
            if ($('.enrolled-courses-list .list-group').length === 0) {
                $('.enrolled-courses-list').html('<div class="list-group">' + enrolledCourseHtml + '</div>');
            } else {
                $('.enrolled-courses-list .list-group').append(enrolledCourseHtml);
            }
            
            // Remove empty state message if it exists
            $('.enrolled-courses-list .text-muted').remove();
        }
        
        function removeCourseFromEnrolledList(courseId) {
            $(`#enrolled-course-${courseId}`).fadeOut(300, function() {
                $(this).remove();
                
                // Show empty state if no courses left
                if ($('.enrolled-courses-list .list-group-item').length === 0) {
                    $('.enrolled-courses-list').html(`
                        <p class="text-muted">You are not enrolled in any courses yet.</p>
                        <p>Browse the available courses below to get started!</p>
                    `);
                }
            });
        }
        
        function updateStats() {
            const enrolledCount = $('.enrolled-courses-list .list-group-item').length;
            const availableCount = $('.available-courses-list .enroll-btn:visible').length;
            
            $('.enrolled-courses-stat').text(enrolledCount);
            $('.available-courses-stat').text(availableCount);
        }
    </script>
</body>
</html>
