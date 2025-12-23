<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script>
        // Block Sentry immediately in head
        (function() {
            const originalFetch = window.fetch;
            const originalXHROpen = XMLHttpRequest.prototype.open;
            
            window.fetch = function(...args) {
                const url = args[0];
                if (typeof url === 'string' && (url.includes('sentry-internal.temp-mail.io') || url.includes('sentry.io'))) {
                    return Promise.resolve(new Response(null, { status: 200 }));
                }
                return originalFetch.apply(this, args);
            };
            
            XMLHttpRequest.prototype.open = function(method, url, ...args) {
                if (typeof url === 'string' && (url.includes('sentry-internal.temp-mail.io') || url.includes('sentry.io'))) {
                    return;
                }
                return originalXHROpen.call(this, method, url, ...args);
            };
        })();
    </script>
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
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url('course') ?>">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('auth/logout') ?>">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1><?= $page_title ?></h1>
        <p><?= $description ?></p>
        
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form id="searchForm" class="d-flex" action="<?= base_url('courses/search') ?>" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search courses..." name="search_term">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
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
                                        <?= esc($course['title']) ?> - <?= esc($course['code'] ?? '') ?>
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
                    <div class="row" id="coursesList">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-6 mb-3 course-item">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <?= esc($course['title']) ?>
                                        </h6>
                                        <p class="card-text">
                                            <small class="text-muted"><?= esc($course['code'] ?? '') ?></small><br>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // AJAX Search functionality
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const searchTerm = document.getElementById('searchInput').value;
            
            fetch('<?= base_url('courses/search') ?>?search_term=' + encodeURIComponent(searchTerm), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCoursesList(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during search. Please try again.');
            });
        });

        function updateCoursesList(data) {
            const coursesList = document.getElementById('coursesList');
            
            if (!data.success || !data.courses) {
                coursesList.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error loading search results.</div></div>';
                return;
            }
            
            const courses = data.courses;
            
            if (courses.length === 0) {
                coursesList.innerHTML = '<div class="col-12"><div class="alert alert-info">No courses found matching your search.</div></div>';
                return;
            }
            
            let html = '';
            courses.forEach(course => {
                html += `
                    <div class="col-md-6 mb-3 course-item">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    ${course.title ? course.title.replace(/</g, '&lt;').replace(/>/g, '&gt;') : ''}
                                </h6>
                                <p class="card-text">
                                    <small class="text-muted">${course.code ? course.code.replace(/</g, '&lt;').replace(/>/g, '&gt;') : ''}</small><br>
                                    ${course.description ? course.description.replace(/</g, '&lt;').replace(/>/g, '&gt;') : ''}
                                </p>
                                <button class="btn btn-sm btn-outline-primary" onclick="selectCourse(${course.id})">
                                    <i class="fas fa-plus me-1"></i>Select
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            coursesList.innerHTML = html;
        }

        // jQuery Client-Side Filtering
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                
                $('.course-item').each(function() {
                    var courseTitle = $(this).find('.card-title').text().toLowerCase();
                    var courseDescription = $(this).find('.card-text').text().toLowerCase();
                    var courseCode = $(this).find('.text-muted').text().toLowerCase();
                    
                    if (courseTitle.includes(searchTerm) || 
                        courseDescription.includes(searchTerm) || 
                        courseCode.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Real-time notifications polling (every 60 seconds)
            setInterval(function() {
                fetchNotifications();
            }, 60000);

            // Initial notification fetch
            fetchNotifications();
        });

        function fetchNotifications() {
            fetch('<?= base_url('notifications/get') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications) {
                    updateNotificationBadge(data.notifications);
                }
            })
            .catch(error => {
                console.log('Notification fetch error:', error);
            });
        }

        function updateNotificationBadge(notifications) {
            var unreadCount = notifications.filter(function(n) {
                return !n.read_at;
            }).length;

            // Update notification badge if it exists
            var notificationBadge = $('.notification-badge');
            if (notificationBadge.length > 0) {
                if (unreadCount > 0) {
                    notificationBadge.text(unreadCount).show();
                } else {
                    notificationBadge.hide();
                }
            }

            // Show toast for new notifications
            if (unreadCount > 0) {
                showNotificationToast('You have ' + unreadCount + ' new notification(s)');
            }
        }

        function showNotificationToast(message) {
            // Create toast notification if not exists
            if ($('#notificationToast').length === 0) {
                $('body').append(`
                    <div id="notificationToast" class="toast position-fixed bottom-0 end-0 m-3" role="alert">
                        <div class="toast-header bg-info text-white">
                            <i class="fas fa-bell me-2"></i>
                            <strong class="me-auto">Notifications</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `);
            }

            var toastElement = document.getElementById('notificationToast');
            var toast = new bootstrap.Toast(toastElement);
            toast.show();
        }
    </script>
</body>
</html>
