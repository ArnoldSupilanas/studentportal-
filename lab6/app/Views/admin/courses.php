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
                            <i class="bi bi-book"></i> <?= esc($page_title) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted"><?= esc($description) ?></p>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Search courses...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end gap-2">
                                    <select class="form-select">
                                        <option>All Status</option>
                                        <option>Published</option>
                                        <option>Draft</option>
                                        <option>Archived</option>
                                    </select>
                                    <button class="btn btn-success" onclick="showAddCourseModal()">
                                        <i class="bi bi-plus-circle"></i> Add Course
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Course Name</th>
                                        <th>Code</th>
                                        <th>Teacher</th>
                                        <th>Students</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="coursesTableBody">
                                <!-- Courses will be dynamically loaded -->
                            </tbody>
                            </table>
                        </div>
                        
                        <nav aria-label="Course pagination">
                            <ul class="pagination justify-content-center" id="coursePagination">
                                <!-- Pagination will be dynamically generated -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="courseForm">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="courseName" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="courseName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="courseCode" class="form-label">Course Code</label>
                            <input type="text" class="form-control" id="courseCode" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="courseTeacher" class="form-label">Teacher</label>
                            <input type="text" class="form-control" id="courseTeacher" name="teacher" required>
                        </div>
                        <div class="mb-3">
                            <label for="courseStatus" class="form-label">Status</label>
                            <select class="form-select" id="courseStatus" name="status" required>
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="saveCourse()">Save Course</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm">
                        <?= csrf_field() ?>
                        <input type="hidden" id="editCourseId" name="id">
                        <div class="mb-3">
                            <label for="editCourseName" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="editCourseName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCourseCode" class="form-label">Course Code</label>
                            <input type="text" class="form-control" id="editCourseCode" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCourseTeacher" class="form-label">Teacher</label>
                            <input type="text" class="form-control" id="editCourseTeacher" name="teacher" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCourseStatus" class="form-label">Status</label>
                            <select class="form-select" id="editCourseStatus" name="status" required>
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateCourse()">Update Course</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Modal -->
    <div class="modal fade" id="studentsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enrolled Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="studentsList">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Modal -->
    <div class="modal fade" id="statsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Course Statistics</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="courseStats">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let csrfTokenName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        function updateCsrf(data) {
            if (data && data.csrf_token && data.csrf_hash) {
                csrfTokenName = data.csrf_token;
                csrfHash = data.csrf_hash;
                const hiddenInputs = document.querySelectorAll('#courseForm input[type="hidden"], #editCourseForm input[type="hidden"]');
                hiddenInputs.forEach(function (el) {
                    if (el.name && el.name.toLowerCase().includes('csrf')) {
                        el.name = csrfTokenName;
                        el.value = csrfHash;
                    }
                });
            }
        }
        // Load courses with pagination
        function loadCourses(page = 1) {
            const search = document.querySelector('input[placeholder="Search courses..."]').value;
            const status = document.querySelector('select').value;
            
            let url = `<?= site_url('admin/course-list') ?>?page=${page}`;
            if (status && status !== 'All Status') url += `&status=${status.toLowerCase()}`;
            if (search) url += `&search=${search}`;

            // Mark request as AJAX so Admin::courseList accepts it
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderCoursesTable(data.courses);
                        renderCoursePagination(data.pagination);
                    } else {
                        console.error('Error loading courses:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Render courses table
        function renderCoursesTable(courses) {
            const tbody = document.getElementById('coursesTableBody');
            
            if (!courses || courses.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No courses found</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            
            courses.forEach(course => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${course.id || 'N/A'}</td>
                    <td>${course.name || course.title || 'Untitled Course'}</td>
                    <td><code>${course.code || course.course_code || 'N/A'}</code></td>
                    <td>${course.teacher || course.teacher_name || 'No Teacher Assigned'}</td>
                    <td>
                        <span class="badge bg-info">${course.students || course.student_count || 0} Students</span>
                    </td>
                    <td>
                        <span class="badge bg-${course.status === 'published' ? 'success' : (course.status === 'draft' ? 'warning' : 'secondary')}">
                            ${course.status ? (course.status.charAt(0).toUpperCase() + course.status.slice(1)) : 'Unknown'}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editCourse(${course.id})">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-info" onclick="viewStudents(${course.id})">
                            <i class="bi bi-people"></i> Students
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="viewStats(${course.id})">
                            <i class="bi bi-graph-up"></i> Stats
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCourse(${course.id})">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Render course pagination
        function renderCoursePagination(pagination) {
            const paginationElement = document.getElementById('coursePagination');
            paginationElement.innerHTML = '';
            
            if (!pagination || pagination.total_pages <= 1) return;
            
            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${pagination.current_page === 1 ? 'disabled' : ''}`;
            const prevLink = document.createElement('a');
            prevLink.className = 'page-link';
            prevLink.href = '#';
            prevLink.textContent = 'Previous';
            prevLink.addEventListener('click', function(e) {
                e.preventDefault();
                if (pagination.current_page > 1) {
                    loadCourses(pagination.current_page - 1);
                }
            });
            prevLi.appendChild(prevLink);
            paginationElement.appendChild(prevLi);
            
            // Page numbers
            for (let i = 1; i <= pagination.total_pages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
                const link = document.createElement('a');
                link.className = 'page-link';
                link.href = '#';
                link.textContent = i;
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadCourses(i);
                });
                li.appendChild(link);
                paginationElement.appendChild(li);
            }
            
            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}`;
            const nextLink = document.createElement('a');
            nextLink.className = 'page-link';
            nextLink.href = '#';
            nextLink.textContent = 'Next';
            nextLink.addEventListener('click', function(e) {
                e.preventDefault();
                if (pagination.current_page < pagination.total_pages) {
                    loadCourses(pagination.current_page + 1);
                }
            });
            nextLi.appendChild(nextLink);
            paginationElement.appendChild(nextLi);
        }

        function showAddCourseModal() {
            document.getElementById('courseForm').reset();
            const modal = new bootstrap.Modal(document.getElementById('addCourseModal'));
            modal.show();
        }

        function saveCourse() {
            const form = document.getElementById('courseForm');
            const formData = new FormData(form);
            
            fetch('<?= site_url('admin/create-course') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                updateCsrf(data);
                if (data.success) {
                    alert('Course added successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('addCourseModal')).hide();
                    loadCourses(); // Refresh the course list
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the course.');
            });
        }

        function editCourse(courseId) {
            // Fetch course data and populate edit modal
            fetch('<?= site_url('admin/get-course') ?>/' + courseId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCsrf(data);
                if (data.success) {
                    document.getElementById('editCourseId').value = data.course.id || '';
                    document.getElementById('editCourseName').value = data.course.name || data.course.title || '';
                    document.getElementById('editCourseCode').value = data.course.code || data.course.course_code || '';
                    document.getElementById('editCourseTeacher').value = data.course.teacher || data.course.teacher_name || '';
                    document.getElementById('editCourseStatus').value = data.course.status || 'draft';
                    
                    const modal = new bootstrap.Modal(document.getElementById('editCourseModal'));
                    modal.show();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching course data.');
            });
        }

        function updateCourse() {
            const form = document.getElementById('editCourseForm');
            const formData = new FormData(form);
            
            fetch('<?= site_url('admin/update-course') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                updateCsrf(data);
                if (data.success) {
                    alert('Course updated successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('editCourseModal')).hide();
                    loadCourses(); // Refresh the course list
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the course.');
            });
        }

        function viewStudents(courseId) {
            const modal = new bootstrap.Modal(document.getElementById('studentsModal'));
            modal.show();
            
            // Fetch students data
            fetch('<?= site_url('admin/course-students') ?>/' + courseId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayStudents(data.students);
                } else {
                    document.getElementById('studentsList').innerHTML = '<p class="text-danger">Error: ' + data.message + '</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('studentsList').innerHTML = '<p class="text-danger">An error occurred while fetching students.</p>';
            });
        }

        function displayStudents(students) {
            let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th></tr></thead><tbody>';
            
            if (students.length === 0) {
                html += '<tr><td colspan="4" class="text-center">No students enrolled in this course.</td></tr>';
            } else {
                students.forEach(student => {
                    html += '<tr><td>' + student.id + '</td><td>' + student.name + '</td><td>' + student.email + '</td><td><span class="badge bg-success">' + student.status + '</span></td></tr>';
                });
            }
            
            html += '</tbody></table></div>';
            document.getElementById('studentsList').innerHTML = html;
        }

        function viewStats(courseId) {
            const modal = new bootstrap.Modal(document.getElementById('statsModal'));
            modal.show();
            
            // Fetch stats data
            fetch('<?= site_url('admin/course-stats') ?>/' + courseId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayStats(data.stats);
                } else {
                    document.getElementById('courseStats').innerHTML = '<p class="text-danger">Error: ' + data.message + '</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('courseStats').innerHTML = '<p class="text-danger">An error occurred while fetching statistics.</p>';
            });
        }

        function displayStats(stats) {
            let html = '<div class="row">';
            html += '<div class="col-md-6"><div class="card"><div class="card-body"><h5 class="card-title">Total Students</h5><h3 class="text-primary">' + stats.total_students + '</h3></div></div></div>';
            html += '<div class="col-md-6"><div class="card"><div class="card-body"><h5 class="card-title">Active Students</h5><h3 class="text-success">' + stats.active_students + '</h3></div></div></div>';
            html += '</div><div class="row mt-3">';
            html += '<div class="col-md-6"><div class="card"><div class="card-body"><h5 class="card-title">Completion Rate</h5><h3 class="text-info">' + stats.completion_rate + '%</h3></div></div></div>';
            html += '<div class="col-md-6"><div class="card"><div class="card-body"><h5 class="card-title">Average Grade</h5><h3 class="text-warning">' + stats.average_grade + '</h3></div></div></div>';
            html += '</div>';
            
            document.getElementById('courseStats').innerHTML = html;
        }

        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course?')) {
                const params = new URLSearchParams();
                params.append('course_id', courseId);
                params.append(csrfTokenName, csrfHash);
                fetch('<?= site_url('admin/delete-course') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params.toString()
                })
                .then(response => response.json())
                .then(data => {
                    updateCsrf(data);
                    if (data.success) {
                        alert('Course deleted successfully!');
                        loadCourses();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the course.');
                });
            }
        }

        // Event listeners for search and filter
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial courses data
            loadCourses(1);
            
            // Add event listener to search input
            const searchInput = document.querySelector('input[placeholder="Search courses..."]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    loadCourses(1); // Reset to first page when searching
                });
            }

            // Add event listener to status filter
            const statusSelect = document.querySelector('select');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    loadCourses(1); // Reset to first page when filtering
                });
            }
        });
    </script>
</body>
</html>
