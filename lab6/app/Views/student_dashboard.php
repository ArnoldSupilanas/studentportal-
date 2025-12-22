<?php include(APPPATH . 'Views/templates/header.php'); ?>

<!-- Dashboard-Specific Styles -->
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
        border-radius: 0 0 20px 20px;
    }
    
    .dashboard-header h1 {
        margin-bottom: 10px;
    }
    
    .dashboard-header p {
        margin-bottom: 0;
        opacity: 0.9;
    }
    
    /* Interactive Button Styles */
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        transform: translateY(0);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
    }
    
    .btn-gradient:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.4);
    }
    
    .btn-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-gradient:hover::before {
        left: 100%;
    }
    
    .btn-gradient.loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .btn-gradient.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        animation: success-pulse 0.6s ease;
    }
    
    @keyframes success-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    /* Course Card Hover Effects */
    .list-group-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        margin-bottom: 10px;
        border-radius: 10px;
    }
    
    .list-group-item:hover {
        transform: translateX(5px);
        border-left-color: #667eea;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    /* Enrollment Animation */
    .enrollment-success {
        animation: slideInRight 0.5s ease, fadeIn 0.5s ease;
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Enhanced Alert Styles */
    .alert-custom {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    /* Stats Card Hover */
    .stats-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stats-card .card-body {
        padding: 30px 20px;
    }
    
    .stats-card h5 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    /* Pulse Animation for New Enrollments */
    .new-enrollment {
        animation: pulse-green 2s infinite;
    }
    
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }
    
    .course-section-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .course-section-card .card-header {
        border: none;
        padding: 20px 25px;
        font-weight: 600;
    }
    
    .course-section-card .card-body {
        padding: 25px;
    }
</style>

<!-- Dashboard Content -->
<div class="container mt-4">
    <div class="dashboard-header">
        <div class="container">
            <h1 class="display-4 fw-bold">Student Dashboard</h1>
            <p class="lead">Welcome back, <?= esc($name ?? 'Student') ?>! Manage your courses and track your progress.</p>
        </div>
    </div>
    
    <div class="container">
        
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
            <div class="col-lg-6 mb-4">
                <div class="card course-section-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-mortarboard-fill me-2"></i>
                            My Enrolled Courses
                        </h5>
                    </div>
                    <div class="card-body enrolled-courses-list">
                        <?php if (!empty($enrolled_courses)): ?>
                            <div class="list-group">
                                <?php foreach ($enrolled_courses as $course): ?>
                                    <div class="list-group-item" id="enrolled-course-<?= $course['course_id'] ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-bold">
                                                    <i class="bi bi-book-fill text-primary me-2"></i>
                                                    <?= esc($course['title']) ?>
                                                </h6>
                                                <p class="mb-1 text-muted"><?= esc(substr($course['description'] ?? '', 0, 100)) ?>...</p>
                                                <small class="text-muted">
                                                    <i class="bi bi-graph-up me-1"></i>
                                                    Progress: <?= $course['progress'] ?? 0 ?>% | 
                                                    <span class="badge bg-success"><?= $course['status'] ?? 'Active' ?></span>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block mb-2">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    Enrolled: <?= date('M j, Y', strtotime($course['enrollment_date'])) ?>
                                                </small>
                                                <button class="btn btn-sm btn-outline-danger unenroll-btn" data_course_id="<?= $course['course_id'] ?>">
                                                    <i class="bi bi-x-circle me-1"></i>Drop
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                <p class="text-muted mb-2">You are not enrolled in any courses yet.</p>
                                <p class="text-muted">Browse the available courses below to get started!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Available Courses Section -->
            <div class="col-lg-6 mb-4">
                <div class="card course-section-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-search me-2"></i>
                            Available Courses
                        </h5>
                    </div>
                    <div class="card-body available-courses-list">
                        <?php if (!empty($available_courses)): ?>
                            <div class="list-group">
                                <?php foreach ($available_courses as $course): ?>
                                    <div class="list-group-item" id="available-course-<?= $course['id'] ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-bold">
                                                    <i class="bi bi-book text-info me-2"></i>
                                                    <?= esc($course['title']) ?>
                                                </h6>
                                                <p class="mb-1 text-muted"><?= esc(substr($course['description'] ?? '', 0, 100)) ?>...</p>
                                                <small class="text-muted">
                                                    <i class="bi bi-person-badge me-1"></i>
                                                    Instructor ID: <?= $course['instructor_id'] ?? 'TBD' ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-sm btn-gradient enroll-btn interactive-btn" 
                                                        data_course_id="<?= $course['id'] ?>"
                                                        data_course_title="<?= esc($course['title']) ?>"
                                                        title="Enroll in <?= esc($course['title']) ?>">
                                                    <i class="fas fa-plus-circle me-1"></i>
                                                    <span class="btn-text">Enroll Now</span>
                                                    <div class="spinner-border spinner-border-sm d-none" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                                <p class="text-muted mb-2">No new courses available at the moment.</p>
                                <p class="text-muted">Check back later for new course offerings!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="text-primary mb-3">
                            <i class="bi bi-mortarboard-fill display-4"></i>
                        </div>
                        <h5 class="card-title enrolled-courses-stat"><?= count($enrolled_courses) ?></h5>
                        <p class="card-text text-muted">Enrolled Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="text-info mb-3">
                            <i class="bi bi-search display-4"></i>
                        </div>
                        <h5 class="card-title available-courses-stat"><?= count($available_courses) ?></h5>
                        <p class="card-text text-muted">Available Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="text-warning mb-3">
                            <i class="bi bi-clipboard-check display-4"></i>
                        </div>
                        <h5 class="card-title">0</h5>
                        <p class="card-text text-muted">Pending Assignments</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="text-success mb-3">
                            <i class="bi bi-award display-4"></i>
                        </div>
                        <h5 class="card-title">A</h5>
                        <p class="card-text text-muted">Current GPA</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript for AJAX Enrollment -->
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
        // Show loading state
        $button.addClass('loading');
        $button.find('.btn-text').text('Enrolling...');
        $button.find('.spinner-border').removeClass('d-none');
        $button.find('i').addClass('d-none');
        
        $.post('<?= base_url('course/enroll') ?>', {
            course_id: courseId
        }, function(response) {
            // Remove loading state
            $button.removeClass('loading');
            $button.find('.spinner-border').addClass('d-none');
            
            if (response.success) {
                // Show success state
                $button.addClass('success');
                $button.find('.btn-text').text('Enrolled!');
                $button.find('i').removeClass('d-none').removeClass('fa-plus-circle').addClass('fa-check-circle');
                $button.prop('disabled', true);
                
                // Show enhanced success alert
                showAlert(response.message, 'success');
                
                // Add course to enrolled list with animation
                setTimeout(function() {
                    addCourseToEnrolledList(response.course_data);
                    updateStats();
                    
                    // Hide the available course item with animation
                    $(`#available-course-${courseId}`).fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 800);
            } else {
                // Reset button and show error
                $button.find('.btn-text').text('Enroll Now');
                $button.find('i').removeClass('d-none');
                showAlert(response.message, 'danger');
            }
        }, 'json')
        .fail(function() {
            // Reset button on failure
            $button.removeClass('loading');
            $button.find('.spinner-border').addClass('d-none');
            $button.find('.btn-text').text('Enroll Now');
            $button.find('i').removeClass('d-none');
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
        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show alert-custom" role="alert">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        ${type === 'success' ? '<i class="fas fa-check-circle fa-lg"></i>' : 
                          type === 'danger' ? '<i class="fas fa-exclamation-triangle fa-lg"></i>' : 
                          '<i class="fas fa-info-circle fa-lg"></i>'}
                    </div>
                    <div class="flex-grow-1">
                        <strong>${type === 'success' ? 'Success!' : type === 'danger' ? 'Error!' : 'Info'}</strong><br>
                        <span class="alert-message">${message}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        `;
        
        // Add alert to the top of the container
        $('.container').prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $(`#${alertId}`).alert('close');
        }, 5000);
    }
    
    function addCourseToEnrolledList(courseData) {
        const enrolledCourseHtml = `
            <div class="list-group-item enrollment-success new-enrollment" id="enrolled-course-${courseData.course_id}">
                <div class="d-flex w-100 justify-content-between">
                    <div>
                        <h6 class="mb-1 fw-bold">
                            <i class="bi bi-book-fill text-primary me-2"></i>
                            ${courseData.title}
                            <span class="badge bg-success ms-2">NEW</span>
                        </h6>
                        <p class="mb-1 text-muted">${courseData.description ? courseData.description.substring(0, 100) + '...' : ''}</p>
                        <small class="text-muted">
                            <i class="bi bi-graph-up me-1"></i>
                            Progress: 0% | 
                            <i class="bi bi-info-circle me-1"></i>
                            Status: <span class="badge bg-success">enrolled</span>
                        </small>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar-check me-1"></i>
                            Enrolled: Just now
                        </small>
                        <button class="btn btn-sm btn-outline-danger unenroll-btn mt-2" data_course_id="${courseData.course_id}">
                            <i class="bi bi-x-circle me-1"></i>Drop
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add to enrolled courses list
        if ($('.enrolled-courses-list .list-group').length === 0) {
            $('.enrolled-courses-list').html('<div class="list-group">' + enrolledCourseHtml + '</div>');
        } else {
            $('.enrolled-courses-list .list-group').prepend(enrolledCourseHtml);
        }
        
        // Remove empty state message if it exists
        $('.enrolled-courses-list .text-muted').remove();
        
        // Remove NEW badge after 3 seconds
        setTimeout(function() {
            $(`#enrolled-course-${courseData.course_id} .badge.bg-success`).fadeOut(500, function() {
                $(this).remove();
            });
            $(`#enrolled-course-${courseData.course_id}`).removeClass('new-enrollment');
        }, 3000);
    }
    
    function removeCourseFromEnrolledList(courseId) {
        $(`#enrolled-course-${courseId}`).fadeOut(300, function() {
            $(this).remove();
            
            // Show empty state if no courses left
            if ($('.enrolled-courses-list .list-group-item').length === 0) {
                $('.enrolled-courses-list').html(`
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-2">You are not enrolled in any courses yet.</p>
                        <p class="text-muted">Browse the available courses below to get started!</p>
                    </div>
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
